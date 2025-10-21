<?php
require_once __DIR__ . '/functions.php';
if (!is_logged_in()) {
    redirect('../public/login.php');
}

if (isset($_GET['id'])) {
    $component_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Get page_id from component_id to redirect back
    $pageIdStmt = pdo()->prepare("SELECT page_id FROM landing_page_components WHERE id = ?");
    $pageIdStmt->execute([$component_id]);
    $page_id = $pageIdStmt->fetchColumn();

    if ($page_id) {
        // Ensure the page belongs to the current user before deleting
        $stmt = pdo()->prepare(
            "DELETE lpc FROM landing_page_components lpc
             JOIN landing_pages lp ON lpc.page_id = lp.id
             WHERE lpc.id = ? AND lp.user_id = ?"
        );
        $stmt->execute([$component_id, $user_id]);

        $_SESSION['message'] = 'Component deleted successfully.';
        redirect('../public/edit_landing_page.php?id=' . $page_id);
    }
}

redirect('../public/landing_pages.php');
