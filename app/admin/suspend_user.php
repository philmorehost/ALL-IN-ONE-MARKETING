<?php
require_once __DIR__ . '/admin_functions.php';
admin_only();

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $stmt = pdo()->prepare("SELECT status FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if ($user) {
        $new_status = $user['status'] === 'active' ? 'suspended' : 'active';
        $updateStmt = pdo()->prepare("UPDATE users SET status = ? WHERE id = ?");
        $updateStmt->execute([$new_status, $user_id]);
    }
}

redirect('../../../public/admin/dashboard.php');
