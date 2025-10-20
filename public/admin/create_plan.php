<?php
require_once '../../app/admin/admin_functions.php';
admin_only();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Plan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card w-50 mx-auto">
            <div class="card-header">
                <h1>Create New Plan</h1>
            </div>
            <div class="card-body">
                <?php require_once '../../app/csrf.php'; ?>
                <form action="../../app/admin/create_plan.php" method="post">
                    <?php echo csrf_input(); ?>
                    <div class="mb-3">
                        <label for="name" class="form-label">Plan Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="features" class="form-label">Features (comma-separated)</label>
                        <textarea class="form-control" id="features" name="features" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Plan</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
