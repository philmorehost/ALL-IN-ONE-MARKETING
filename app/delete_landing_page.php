<?php
require_once __DIR__ . '/functions.php';
if (!is_logged_in()) {
    redirect('../public/login.php');
}

if (isset($_GET['id'])) {
    $page_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Ensure the page belongs to the current user before deleting
    $stmt = pdo()->prepare("DELETE FROM landing_pages WHERE id = ? AND user_id = ?");
    $stmt->execute([$page_id, $user_id]);

    $_SESSION['message'] = 'Landing page deleted successfully.';
}

redirect('../public/landing_pages.php');
