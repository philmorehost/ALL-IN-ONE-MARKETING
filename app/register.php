<?php
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = pdo()->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);
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
