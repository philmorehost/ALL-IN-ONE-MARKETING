<?php
require_once '../../app/admin/admin_functions.php';
admin_only();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page Editor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="landing_page_editor.php">Landing Page</a></li>
                </ul>
            </div>
            <a href="../../app/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>
    <div class="container mt-5">
        <h1>Landing Page Editor</h1>

        <ul class="nav nav-tabs" id="pageTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="sections-tab" data-bs-toggle="tab" data-bs-target="#sections" type="button" role="tab">Content Sections</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="slider-tab" data-bs-toggle="tab" data-bs-target="#slider" type="button" role="tab">Header Slides</button>
            </li>
        </ul>

        <div class="tab-content" id="pageTabsContent">
            <!-- Content Sections Tab -->
            <div class="tab-pane fade show active" id="sections" role="tabpanel">
                <h2 class="mt-3">Edit Content Sections</h2>
                <!-- Placeholder for section editor form -->
            </div>

            <!-- Header Slides Tab -->
            <div class="tab-pane fade" id="slider" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Manage Header Slides</h2>
                    <button class="btn btn-primary">Add New Slide</button>
                </div>
                <table class="table mt-3">
                    <thead><tr><th>Image</th><th>Link</th><th>Actions</th></tr></thead>
                    <tbody><!-- Placeholder --></tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
