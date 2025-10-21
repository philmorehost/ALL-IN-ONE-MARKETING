<?php
require_once __DIR__ . '/functions.php';
if (!is_logged_in()) {
    redirect('../public/login.php');
}

if (isset($_GET['id'])) {
    $contact_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Get list_id from contact_id to redirect back
    $listIdStmt = pdo()->prepare("SELECT list_id FROM contacts WHERE id = ?");
    $listIdStmt->execute([$contact_id]);
    $list_id = $listIdStmt->fetchColumn();

    if ($list_id) {
        // Ensure the list belongs to the current user before deleting
        $stmt = pdo()->prepare(
            "DELETE c FROM contacts c
             JOIN contact_lists cl ON c.list_id = cl.id
             WHERE c.id = ? AND cl.user_id = ?"
        );
        $stmt->execute([$contact_id, $user_id]);

        $_SESSION['message'] = 'Contact deleted successfully.';
        redirect('../public/view_list.php?id=' . $list_id);
    }
}

redirect('../public/email_marketing.php');
