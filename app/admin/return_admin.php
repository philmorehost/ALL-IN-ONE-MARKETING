<?php
require_once __DIR__ . '/admin_functions.php';

if (isset($_SESSION['original_user_id'])) {
    $_SESSION['user_id'] = $_SESSION['original_user_id'];
    $_SESSION['is_admin'] = true;
    unset($_SESSION['original_user_id']);

    redirect('../../../public/admin/dashboard.php');
} else {
    redirect('../../../public/dashboard.php');
}
