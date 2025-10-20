<?php
require_once __DIR__ . '/admin_functions.php';
require_once __DIR__ . '/../csrf.php';
admin_only();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token();
    $name = $_POST['name'];
    $price = $_POST['price'];
    $features = $_POST['features'];

    $stmt = pdo()->prepare("INSERT INTO plans (name, price, features) VALUES (?, ?, ?)");
    $stmt->execute([$name, $price, $features]);
}

redirect('../../../public/admin/plans.php');
