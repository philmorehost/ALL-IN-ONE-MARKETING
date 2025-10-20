<?php
require_once __DIR__ . '/admin_functions.php';
admin_only();

if (isset($_GET['id'])) {
    $payment_id = $_GET['id'];

    $stmt = pdo()->prepare("UPDATE payments SET status = 'failed' WHERE id = ?");
    $stmt->execute([$payment_id]);
}

redirect('../../../public/admin/payments.php');
