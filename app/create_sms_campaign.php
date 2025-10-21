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
    $sender_id = $_POST['sender_id'];
    $message = $_POST['message'];

    // Verify that the list belongs to the current user
    $listStmt = pdo()->prepare("SELECT id FROM contact_lists WHERE id = ? AND user_id = ?");
    $listStmt->execute([$list_id, $user_id]);
    if ($listStmt->fetch()) {
        $pdo = pdo();
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare(
                "INSERT INTO sms_campaigns (user_id, list_id, sender_id, message, status, sent_at) VALUES (?, ?, ?, ?, 'sent', NOW())"
            );
            $stmt->execute([$user_id, $list_id, $sender_id, $message]);

            // Placeholder for sending SMS via PhilmoreSMS API
            $contactsStmt = $pdo->prepare("SELECT email FROM contacts WHERE list_id = ?");
            $contactsStmt->execute([$list_id]);
            $contacts = $contactsStmt->fetchAll(\PDO::FETCH_COLUMN);

            $log_message = "--- New SMS Campaign ---\n";
            $log_message .= "Sender ID: {$sender_id}\n";
            $log_message .= "Message: {$message}\n";
            $log_message .= "Recipients: " . implode(', ', $contacts) . "\n\n";
            file_put_contents(__DIR__ . '/../sms.log', $log_message, FILE_APPEND);

            $pdo->commit();
            $_SESSION['message'] = 'SMS campaign sent successfully.';

        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['error'] = 'An error occurred while sending the SMS campaign.';
        }
    } else {
        $_SESSION['error'] = 'Invalid contact list.';
    }
}

redirect('../public/sms_marketing.php');
