<?php
require_once __DIR__ . '/functions.php';
if (!is_logged_in()) {
    redirect('../public/login.php');
}

if (isset($_GET['id'])) {
    $list_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Ensure the list belongs to the current user before deleting
    $stmt = pdo()->prepare("DELETE FROM contact_lists WHERE id = ? AND user_id = ?");
    $stmt->execute([$list_id, $user_id]);

    $_SESSION['message'] = 'Contact list deleted successfully.';
}

redirect('../public/email_marketing.php');
