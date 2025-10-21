<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/csrf.php';
if (!is_logged_in()) {
    redirect('../public/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token();

    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9-]+/', '-', $_POST['slug'])));

    // Check if slug is unique
    $slugStmt = pdo()->prepare("SELECT id FROM landing_pages WHERE slug = ?");
    $slugStmt->execute([$slug]);
    if ($slugStmt->fetch()) {
        $_SESSION['error'] = 'This URL slug is already taken. Please choose another.';
        redirect('../public/create_landing_page.php');
    }

    $stmt = pdo()->prepare("INSERT INTO landing_pages (user_id, title, slug) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $title, $slug]);
    $page_id = pdo()->lastInsertId();

    redirect('../public/edit_landing_page.php?id=' . $page_id);
}
