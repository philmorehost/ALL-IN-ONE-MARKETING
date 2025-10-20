<?php
require_once '../../app/admin/admin_functions.php';
admin_only();

$stmt = pdo()->query(
    "SELECT p.*, u.name as user_name, pl.name as plan_name
     FROM payments p
     JOIN users u ON p.user_id = u.id
     JOIN subscriptions s ON p.subscription_id = s.id
     JOIN plans pl ON s.plan_id = pl.id
     ORDER BY p.created_at DESC"
);
$payments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payments</title>
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
        <h1>Payment Management</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Plan</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Proof</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($payment['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($payment['plan_name']); ?></td>
                        <td>$<?php echo htmlspecialchars($payment['amount']); ?></td>
                        <td><?php echo ucfirst($payment['payment_type']); ?></td>
                        <td><?php echo ucfirst($payment['status']); ?></td>
                        <td>
                            <?php if ($payment['proof_of_payment']): ?>
                                <a href="../uploads/<?php echo htmlspecialchars($payment['proof_of_payment']); ?>" target="_blank">View Proof</a>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($payment['status'] === 'pending'): ?>
                                <a href="../../app/admin/approve_payment.php?id=<?php echo $payment['id']; ?>" class="btn btn-sm btn-success">Approve</a>
                                <a href="../../app/admin/reject_payment.php?id=<?php echo $payment['id']; ?>" class="btn btn-sm btn-danger">Reject</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
