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
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ai_content.php">AI Content Assistant</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="social_accounts.php">Social Accounts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="calendar.php">Content Calendar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="email_marketing.php">Email Marketing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sms_marketing.php">SMS Marketing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="landing_pages.php">Landing Pages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="qr_codes.php">QR Codes</a>
                    </li>
                </ul>
            </div>
            <a href="../app/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>
    <div class="container mt-5">
        <?php if (isset($_SESSION['original_user_id'])): ?>
            <div class="alert alert-warning">
                You are currently logged in as another user.
                <a href="../app/admin/return_admin.php">Return to your admin account</a>.
            </div>
        <?php endif; ?>
        <?php
            $user_id = $_SESSION['user_id'];
            $userStmt = pdo()->prepare("SELECT * FROM users WHERE id = ?");
            $userStmt->execute([$user_id]);
            $user = $userStmt->fetch();

            $subStmt = pdo()->prepare(
                "SELECT s.*, p.name as plan_name
                 FROM subscriptions s
                 JOIN plans p ON s.plan_id = p.id
                 WHERE s.user_id = ? AND s.ends_at > NOW()
                 ORDER BY s.ends_at DESC LIMIT 1"
            );
            $subStmt->execute([$user_id]);
            $subscription = $subStmt->fetch();
        ?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
            <a href="../app/logout.php" class="btn btn-danger">Logout</a>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Subscription Status</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $subscription ? 'Active' : 'Inactive'; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Current Plan</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $subscription ? htmlspecialchars($subscription['plan_name']) : 'N/A'; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Subscription Ends</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $subscription ? date('F j, Y', strtotime($subscription['ends_at'])) : 'N/A'; ?></h5>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!$subscription): ?>
        <div class="row mt-4">
            <h2>Choose a Plan to Get Started</h2>
            <?php
                $stmt = pdo()->query("SELECT * FROM plans");
                $plans = $stmt->fetchAll();
                foreach ($plans as $plan):
            ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3><?php echo htmlspecialchars($plan['name']); ?></h3>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title">$<?php echo htmlspecialchars($plan['price']); ?></h4>
                            <p class="card-text">
                                <?php
                                    $features = explode(',', htmlspecialchars($plan['features']));
                                    echo '<ul>';
                                    foreach ($features as $feature) {
                                        echo '<li>' . trim($feature) . '</li>';
                                    }
                                    echo '</ul>';
                                ?>
                            </p>
                            <a href="subscribe.php?plan_id=<?php echo $plan['id']; ?>" class="btn btn-primary">Subscribe</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
