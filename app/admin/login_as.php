<?php
require_once __DIR__ . '/admin_functions.php';
admin_only();

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Store original admin ID in session to allow switching back
    $_SESSION['original_user_id'] = $_SESSION['user_id'];

    // Switch to the new user's session
    $_SESSION['user_id'] = $user_id;
    unset($_SESSION['is_admin']); // No longer an admin in this session

    redirect('../../../public/dashboard.php');
} else {
    redirect('../../../public/admin/dashboard.php');
}
