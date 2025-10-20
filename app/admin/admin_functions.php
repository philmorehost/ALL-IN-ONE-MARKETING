<?php
require_once __DIR__ . '/../functions.php';

function admin_only() {
    if (!isset($_SESSION['is_admin'])) {
        redirect('../../public/admin/index.php');
    }
}
