<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../config/api_keys.php';
if (!is_logged_in()) {
    redirect('../public/login.php');
}

if (isset($_GET['reference'])) {
    $reference = $_GET['reference'];

    // Verify the transaction with Paystack
    $url = 'https://api.paystack.co/transaction/verify/' . rawurlencode($reference);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . PAYSTACK_SECRET_KEY
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($result['status'] && $result['data']['status'] === 'success') {
        $user_id = $_SESSION['user_id'];
        $amount = $result['data']['amount'] / 100; // Amount is in kobo

        // Find the plan that matches the amount
        $planStmt = pdo()->prepare("SELECT * FROM plans WHERE price = ?");
        $planStmt->execute([$amount]);
        $plan = $planStmt->fetch();

        if ($plan) {
            $pdo = pdo();
            try {
                $pdo->beginTransaction();

                // Create subscription
                $subStmt = $pdo->prepare("INSERT INTO subscriptions (user_id, plan_id) VALUES (?, ?)");
                $subStmt->execute([$user_id, $plan['id']]);
                $subscription_id = $pdo->lastInsertId();

                // Create payment record
                $paymentStmt = $pdo->prepare(
                    "INSERT INTO payments (user_id, subscription_id, amount, payment_type, transaction_ref, status)
                     VALUES (?, ?, ?, 'paystack', ?, 'completed')"
                );
                $paymentStmt->execute([$user_id, $subscription_id, $amount, $reference]);

                // Activate subscription
                $date = new DateTime();
                $date->modify('+1 month');
                $ends_at = $date->format('Y-m-d H:i:s');
                $subUpdateStmt = pdo()->prepare("UPDATE subscriptions SET ends_at = ? WHERE id = ?");
                $subUpdateStmt->execute([$ends_at, $subscription_id]);

                $pdo->commit();

                $_SESSION['message'] = 'Your payment was successful and your subscription is now active.';
                redirect('../public/dashboard.php');

            } catch (Exception $e) {
                $pdo->rollBack();
                $_SESSION['error'] = 'An error occurred while processing your subscription: ' . $e->getMessage();
                redirect('../public/dashboard.php');
            }
        } else {
            $_SESSION['error'] = 'Payment amount does not match any subscription plan.';
            redirect('../public/dashboard.php');
        }
    } else {
        $_SESSION['error'] = 'Paystack payment verification failed.';
        redirect('../public/dashboard.php');
    }
} else {
    redirect('../public/dashboard.php');
}
