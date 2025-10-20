<?php
require_once __DIR__ . '/functions.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = pdo()->prepare("SELECT * FROM users WHERE email_verify_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $updateStmt = pdo()->prepare(
            "UPDATE users SET email_verified_at = NOW(), email_verify_token = NULL WHERE id = ?"
        );
        $updateStmt->execute([$user['id']]);

        $_SESSION['message'] = 'Your email has been verified successfully. You can now log in.';
        redirect('../public/login.php');
    } else {
        $_SESSION['error'] = 'Invalid verification token.';
        redirect('../public/login.php');
    }
} else {
    redirect('../public/login.php');
}
