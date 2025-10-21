<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/csrf.php';
if (!is_logged_in()) {
    redirect('../public/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token();

    $user_id = $_SESSION['user_id'];
    $content = $_POST['content'];
    $accounts = $_POST['accounts'] ?? [];
    $scheduled_at = !empty($_POST['scheduled_at']) ? date('Y-m-d H:i:s', strtotime($_POST['scheduled_at'])) : null;

    if (empty($accounts)) {
        $_SESSION['error'] = 'Please select at least one social media account to publish to.';
        redirect('../public/calendar.php');
    }

    $pdo = pdo();
    try {
        $pdo->beginTransaction();

        // Create the post
        $postStmt = $pdo->prepare(
            "INSERT INTO posts (user_id, content, status, scheduled_at) VALUES (?, ?, 'scheduled', ?)"
        );
        $postStmt->execute([$user_id, $content, $scheduled_at]);
        $post_id = $pdo->lastInsertId();

        // Associate the post with the selected accounts
        $paStmt = $pdo->prepare("INSERT INTO post_accounts (post_id, account_id) VALUES (?, ?)");
        foreach ($accounts as $account_id) {
            $paStmt->execute([$post_id, $account_id]);
        }

        $pdo->commit();

        $_SESSION['message'] = 'Your post has been scheduled successfully.';

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = 'An error occurred while scheduling your post: ' . $e->getMessage();
    }
}

redirect('../public/calendar.php');
