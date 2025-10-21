<?php
require_once __DIR__ . '/functions.php';
if (!is_logged_in()) {
    redirect('../public/login.php');
}

if (isset($_GET['id'])) {
    $account_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Ensure the account belongs to the current user before deleting
    $stmt = pdo()->prepare("DELETE FROM social_accounts WHERE id = ? AND user_id = ?");
    $stmt->execute([$account_id, $user_id]);

    $_SESSION['message'] = 'Account disconnected successfully.';
}

redirect('../public/social_accounts.php');
