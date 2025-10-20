<?php
require_once '../../app/admin/admin_functions.php';
admin_only();

$stmt = pdo()->query("SELECT * FROM plans");
$plans = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Plans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="plans.php">Plans</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="payments.php">Payments</a>
                    </li>
                </ul>
            </div>
            <a href="../../app/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>
    <div class="container mt-5">
        <h1>Plan Management</h1>
        <a href="create_plan.php" class="btn btn-success mb-3">Create New Plan</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plans as $plan): ?>
                    <tr>
                        <td><?php echo $plan['id']; ?></td>
                        <td><?php echo htmlspecialchars($plan['name']); ?></td>
                        <td>$<?php echo htmlspecialchars($plan['price']); ?></td>
                        <td>
                            <a href="edit_plan.php?id=<?php echo $plan['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="../../app/admin/delete_plan.php?id=<?php echo $plan['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
