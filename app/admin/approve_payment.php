<?php
require_once __DIR__ . '/admin_functions.php';
admin_only();

if (isset($_GET['id'])) {
    $payment_id = $_GET['id'];

    $pdo = pdo();
    try {
        $pdo->beginTransaction();

        // Update payment status
        $paymentStmt = $pdo->prepare("UPDATE payments SET status = 'completed' WHERE id = ?");
        $paymentStmt->execute([$payment_id]);

        // Get subscription ID from payment
        $subIdStmt = $pdo->prepare("SELECT subscription_id FROM payments WHERE id = ?");
        $subIdStmt->execute([$payment_id]);
        $subscription_id = $subIdStmt->fetchColumn();

        // Activate subscription
        if ($subscription_id) {
            $date = new DateTime();
            $date->modify('+1 month');
            $ends_at = $date->format('Y-m-d H:i:s');

            $subStmt = $pdo->prepare("UPDATE subscriptions SET ends_at = ? WHERE id = ?");
            $subStmt->execute([$ends_at, $subscription_id]);
        }

        $pdo->commit();

    } catch (Exception $e) {
        $pdo->rollBack();
        // Optionally log the error
    }
}

redirect('../../../public/admin/payments.php');
