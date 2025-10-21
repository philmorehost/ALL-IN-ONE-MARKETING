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

    $stmt = pdo()->prepare("INSERT INTO contact_lists (user_id, name) VALUES (?, ?)");
    $stmt->execute([$user_id, $name]);

    $_SESSION['message'] = 'Contact list created successfully.';
}

redirect('../public/email_marketing.php');
