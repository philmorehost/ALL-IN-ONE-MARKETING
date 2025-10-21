<?php
require_once __DIR__ . '/functions.php';

if (isset($_GET['id'])) {
    $code_id = $_GET['id'];

    $stmt = pdo()->prepare("SELECT url FROM qr_codes WHERE id = ?");
    $stmt->execute([$code_id]);
    $url = $stmt->fetchColumn();

    if ($url) {
        $updateStmt = pdo()->prepare("UPDATE qr_codes SET scan_count = scan_count + 1 WHERE id = ?");
        $updateStmt->execute([$code_id]);

        header('Location: ' . $url);
        exit;
    }
}

// If the QR code is invalid, redirect to the main site
header('Location: ../index.php');
