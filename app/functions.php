<?php
session_start();
require_once __DIR__ . '/config_loader.php';

function pdo() {
    static $pdo;
    if (!$pdo) {
        require_once __DIR__ . '/../config/database.php';
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $pdo;
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}
