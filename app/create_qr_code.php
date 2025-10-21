<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/csrf.php';
if (!is_logged_in()) {
    redirect('../public/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token();

    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $url = $_POST['url'];

    $stmt = pdo()->prepare("INSERT INTO qr_codes (user_id, name, url) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $name, $url]);

    $_SESSION['message'] = 'QR Code created successfully.';
}

redirect('../public/qr_codes.php');
