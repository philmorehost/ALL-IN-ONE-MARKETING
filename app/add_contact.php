<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/csrf.php';
if (!is_logged_in()) {
    redirect('../public/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf_token();

    $user_id = $_SESSION['user_id'];
    $list_id = $_POST['list_id'];
    $email = $_POST['email'];

    // Verify that the list belongs to the current user
    $listStmt = pdo()->prepare("SELECT id FROM contact_lists WHERE id = ? AND user_id = ?");
    $listStmt->execute([$list_id, $user_id]);
    if ($listStmt->fetch()) {
        // Check if contact already exists in this list
        $contactStmt = pdo()->prepare("SELECT id FROM contacts WHERE list_id = ? AND email = ?");
        $contactStmt->execute([$list_id, $email]);
        if (!$contactStmt->fetch()) {
            $addStmt = pdo()->prepare("INSERT INTO contacts (list_id, email) VALUES (?, ?)");
            $addStmt->execute([$list_id, $email]);
            $_SESSION['message'] = 'Contact added successfully.';
        } else {
            $_SESSION['error'] = 'This contact is already in the list.';
        }
    } else {
        $_SESSION['error'] = 'Invalid list.';
    }
}

redirect('../public/view_list.php?id=' . $list_id);
