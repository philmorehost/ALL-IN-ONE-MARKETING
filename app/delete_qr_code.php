<?php
require_once __DIR__ . '/functions.php';
if (!is_logged_in()) {
    redirect('../public/login.php');
}

if (isset($_GET['id'])) {
    $code_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Ensure the code belongs to the current user before deleting
    $stmt = pdo()->prepare("DELETE FROM qr_codes WHERE id = ? AND user_id = ?");
    $stmt->execute([$code_id, $user_id]);

    $_SESSION['message'] = 'QR Code deleted successfully.';
}

redirect('../public/qr_codes.php');
