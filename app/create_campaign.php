<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/csrf.php';
if (!is_logged_in()) {
    redirect('../public/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token();

    $user_id = $_SESSION['user_id'];
    $list_id = $_POST['list_id'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];

    // Verify that the list belongs to the current user
    $listStmt = pdo()->prepare("SELECT id FROM contact_lists WHERE id = ? AND user_id = ?");
    $listStmt->execute([$list_id, $user_id]);
    if ($listStmt->fetch()) {
        $stmt = pdo()->prepare(
            "INSERT INTO campaigns (user_id, list_id, subject, body, status) VALUES (?, ?, ?, ?, 'draft')"
        );
        $stmt->execute([$user_id, $list_id, $subject, $body]);
        $_SESSION['message'] = 'Campaign saved successfully.';
    } else {
        $_SESSION['error'] = 'Invalid contact list.';
    }
}

redirect('../public/email_marketing.php');
