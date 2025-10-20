<?php
require_once __DIR__ . '/admin_functions.php';
admin_only();

if (isset($_GET['id'])) {
    $plan_id = $_GET['id'];

    $stmt = pdo()->prepare("DELETE FROM plans WHERE id = ?");
    $stmt->execute([$plan_id]);
}

redirect('../../../public/admin/plans.php');
