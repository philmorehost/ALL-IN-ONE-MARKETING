<?php
require_once '../app/functions.php';
if (!is_logged_in()) {
    redirect('login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Landing Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Create New Landing Page</h1>
        <div class="card">
            <div class="card-body">
                <form action="../app/create_landing_page.php" method="post">
                    <?php require_once '../app/csrf.php'; echo csrf_input(); ?>
                    <div class="mb-3">
                        <label for="title" class="form-label">Page Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="slug" class="form-label">URL Slug (e.g., 'my-bio-link')</label>
                        <input type="text" class="form-control" id="slug" name="slug" required pattern="[a-zA-Z0-9-]+">
                        <div class="form-text">Only letters, numbers, and hyphens are allowed.</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Page & Add Content</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
