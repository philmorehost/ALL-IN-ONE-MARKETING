<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/csrf.php';
if (!is_logged_in()) {
    redirect('../public/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token();

    $user_id = $_SESSION['user_id'];
    $page_id = $_POST['page_id'];
    $type = $_POST['type'];
    $content = $_POST['content'];

    // If content is an array (like for links), encode it as JSON
    if (is_array($content)) {
        $content = json_encode($content);
    }

    // Verify that the page belongs to the current user
    $pageStmt = pdo()->prepare("SELECT id FROM landing_pages WHERE id = ? AND user_id = ?");
    $pageStmt->execute([$page_id, $user_id]);
    if ($pageStmt->fetch()) {
        $stmt = pdo()->prepare(
            "INSERT INTO landing_page_components (page_id, type, content) VALUES (?, ?, ?)"
        );
        $stmt->execute([$page_id, $type, $content]);
    } else {
        $_SESSION['error'] = 'Invalid page.';
    }
}

redirect('../public/edit_landing_page.php?id=' . $page_id);
