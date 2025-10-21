<?php
require_once '../app/functions.php';
if (!is_logged_in()) {
    redirect('login.php');
}
$user_id = $_SESSION['user_id'];
$pagesStmt = pdo()->prepare("SELECT * FROM landing_pages WHERE user_id = ?");
$pagesStmt->execute([$user_id]);
$pages = $pagesStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Pages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="landing_pages.php">Landing Pages</a></li>
                    <li class="nav-item"><a class="nav-link" href="qr_codes.php">QR Codes</a></li>
                </ul>
            </div>
            <a href="../app/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>
    <div class="container mt-5">
        <h1>Landing Pages</h1>
        <a href="create_landing_page.php" class="btn btn-primary mb-3">Create New Page</a>

        <div class="card">
            <div class="card-header">Your Pages</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pages as $page): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($page['title']); ?></td>
                            <td>/<?php echo htmlspecialchars($page['slug']); ?></td>
                            <td>
                                <a href="view_page.php?slug=<?php echo $page['slug']; ?>" class="btn btn-sm btn-info" target="_blank">View</a>
                                <a href="edit_landing_page.php?id=<?php echo $page['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="../app/delete_landing_page.php?id=<?php echo $page['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
