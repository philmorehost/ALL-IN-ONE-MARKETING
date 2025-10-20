<?php
require_once 'functions.php';
require_once 'csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token();
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $verify_token = bin2hex(random_bytes(32));

    try {
        $stmt = pdo()->prepare("INSERT INTO users (name, email, password, email_verify_token) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $verify_token]);

        // Send verification email (simulated)
        $verification_link = "http://localhost:8000/app/verify_email.php?token=" . $verify_token;
        $email_body = "Hello {$name},\n\nPlease click the following link to verify your email address:\n{$verification_link}\n\nThank you,\nThe All-in-One Digital Team";

        // Log the email to a file instead of sending it
        file_put_contents(__DIR__ . '/../email.log', "To: {$email}\nSubject: Verify Your Email Address\n\n{$email_body}\n\n---\n\n", FILE_APPEND);

        // Queue a welcome email
        $job_payload = json_encode(['email' => $email, 'name' => $name]);
        $job_handler = 'App\\Jobs\\SendWelcomeEmail';
        $jobStmt = pdo()->prepare("INSERT INTO jobs (handler, payload, run_at) VALUES (?, ?, ?)");
        $jobStmt->execute([$job_handler, $job_payload, date('Y-m-d H:i:s')]);

        $_SESSION['message'] = "Registration successful! Please check your email to verify your account.";

        redirect('../public/login.php');
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') { // Integrity constraint violation (duplicate entry)
            $_SESSION['error'] = 'Email already exists.';
        } else {
            $_SESSION['error'] = 'An error occurred.';
        }
        redirect('../public/register.php');
    }
}
