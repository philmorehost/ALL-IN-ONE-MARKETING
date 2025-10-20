<?php
require_once 'functions.php';
require_once 'csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token();
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = pdo()->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['email_verified_at'] === null) {
            $_SESSION['error'] = 'Please verify your email address before logging in.';
            redirect('../public/login.php');
        }
        $_SESSION['user_id'] = $user['id'];
        redirect('../public/dashboard.php');
    } else {
        $_SESSION['error'] = 'Invalid credentials.';
        redirect('../public/login.php');
    }
}
