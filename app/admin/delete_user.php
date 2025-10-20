<?php
require_once __DIR__ . '/admin_functions.php';
admin_only();

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $stmt = pdo()->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
}

redirect('../../../public/admin/dashboard.php');
