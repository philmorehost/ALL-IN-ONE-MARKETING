<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/csrf.php';
if (!is_logged_in()) {
    redirect('../public/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token();
    $plan_id = $_POST['plan_id'];
    $user_id = $_SESSION['user_id'];

    // Get plan details
    $planStmt = pdo()->prepare("SELECT * FROM plans WHERE id = ?");
    $planStmt->execute([$plan_id]);
    $plan = $planStmt->fetch();

    if ($plan && isset($_FILES['proof']) && $_FILES['proof']['error'] === UPLOAD_ERR_OK) {

        // File validation
        $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
        $max_size = 2 * 1024 * 1024; // 2MB

        if (!in_array($_FILES['proof']['type'], $allowed_types)) {
            $_SESSION['error'] = 'Invalid file type. Please upload a JPG, PNG, or PDF.';
            redirect('../public/subscribe.php?plan_id=' . $plan_id);
        }
        if ($_FILES['proof']['size'] > $max_size) {
            $_SESSION['error'] = 'File is too large. Please upload a file smaller than 2MB.';
            redirect('../public/subscribe.php?plan_id=' . $plan_id);
        }

        $uploadDir = __DIR__ . '/../public/uploads/';
        $fileName = uniqid() . '-' . basename($_FILES['proof']['name']);
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['proof']['tmp_name'], $uploadFile)) {
            $pdo = pdo();
            try {
                $pdo->beginTransaction();

                // Create subscription
                $subStmt = $pdo->prepare("INSERT INTO subscriptions (user_id, plan_id) VALUES (?, ?)");
                $subStmt->execute([$user_id, $plan_id]);
                $subscription_id = $pdo->lastInsertId();

                // Create payment record
                $paymentStmt = $pdo->prepare(
                    "INSERT INTO payments (user_id, subscription_id, amount, payment_type, proof_of_payment, status)
                     VALUES (?, ?, ?, 'manual', ?, 'pending')"
                );
                $paymentStmt->execute([$user_id, $subscription_id, $plan['price'], $fileName]);

                $pdo->commit();

                $_SESSION['message'] = 'Your proof of payment has been submitted and is pending review.';
                redirect('../public/dashboard.php');

            } catch (Exception $e) {
                $pdo->rollBack();
                $_SESSION['error'] = 'An error occurred: ' . $e->getMessage();
                redirect('../public/subscribe.php?plan_id=' . $plan_id);
            }
        } else {
            $_SESSION['error'] = 'Failed to upload proof of payment.';
            redirect('../public/subscribe.php?plan_id=' . $plan_id);
        }
    } else {
        $_SESSION['error'] = 'Invalid request.';
        redirect('../public/subscribe.php?plan_id=' . $plan_id);
    }
}
