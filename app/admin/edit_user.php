<?php
require_once __DIR__ . '/admin_functions.php';
require_once __DIR__ . '/../csrf.php';
admin_only();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token();
    $user_id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $is_admin = $_POST['is_admin'];

    $stmt = pdo()->prepare("UPDATE users SET name = ?, email = ?, is_admin = ? WHERE id = ?");
    $stmt->execute([$name, $email, $is_admin, $user_id]);
}

redirect('../../../public/admin/dashboard.php');
