<?php
require_once __DIR__ . '/admin_functions.php';
require_once __DIR__ . '/../csrf.php';
admin_only();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token();
    $plan_id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $features = $_POST['features'];

    $stmt = pdo()->prepare("UPDATE plans SET name = ?, price = ?, features = ? WHERE id = ?");
    $stmt->execute([$name, $price, $features, $plan_id]);
}

redirect('../../../public/admin/plans.php');
