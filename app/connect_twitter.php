<?php
require_once __DIR__ . '/functions.php';
if (!is_logged_in()) {
    redirect('../public/login.php');
}

$user_id = $_SESSION['user_id'];
$platform = 'twitter';
$username = 'dummy_twitter_user';
$access_token = 'dummy_twitter_token';

try {
    // Check if this account is already connected
    $checkStmt = pdo()->prepare("SELECT * FROM social_accounts WHERE user_id = ? AND platform = ? AND username = ?");
    $checkStmt->execute([$user_id, $platform, $username]);
    if ($checkStmt->fetch()) {
        $_SESSION['message'] = 'This Twitter account is already connected.';
    } else {
        $stmt = pdo()->prepare("INSERT INTO social_accounts (user_id, platform, username, access_token) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $platform, $username, $access_token]);
        $_SESSION['message'] = 'Twitter account connected successfully.';
    }
} catch (Exception $e) {
    $_SESSION['error'] = 'An error occurred while connecting your Twitter account.';
}

redirect('../public/social_accounts.php');
