<?php
require_once '../../app/admin/admin_functions.php';
admin_only();

$plan = null;
if (isset($_GET['id'])) {
    $stmt = pdo()->prepare("SELECT * FROM plans WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $plan = $stmt->fetch();
}

if (!$plan) {
    redirect('plans.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Plan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card w-50 mx-auto">
            <div class="card-header">
                <h1>Edit Plan</h1>
            </div>
            <div class="card-body">
                <?php require_once '../../app/csrf.php'; ?>
                <form action="../../app/admin/edit_plan.php" method="post">
                    <?php echo csrf_input(); ?>
                    <input type="hidden" name="id" value="<?php echo $plan['id']; ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Plan Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($plan['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($plan['price']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="features" class="form-label">Features (comma-separated)</label>
                        <textarea class="form-control" id="features" name="features" rows="3"><?php echo htmlspecialchars($plan['features']); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Plan</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
