<?php
require_once '../app/functions.php';
if (!is_logged_in()) {
    redirect('login.php');
}
$user_id = $_SESSION['user_id'];
$stmt = pdo()->prepare("SELECT * FROM social_accounts WHERE user_id = ?");
$stmt->execute([$user_id]);
$accounts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Social Accounts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="ai_content.php">AI Content Assistant</a></li>
                    <li class="nav-item"><a class="nav-link" href="social_accounts.php">Social Accounts</a></li>
                    <li class="nav-item"><a class="nav-link" href="calendar.php">Content Calendar</a></li>
                    <li class="nav-item"><a class="nav-link" href="email_marketing.php">Email Marketing</a></li>
                    <li class="nav-item"><a class="nav-link" href="sms_marketing.php">SMS Marketing</a></li>
                    <li class="nav-item"><a class="nav-link" href="landing_pages.php">Landing Pages</a></li>
                    <li class="nav-item"><a class="nav-link" href="qr_codes.php">QR Codes</a></li>
                </ul>
            </div>
            <a href="../app/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>
    <div class="container mt-5">
        <h1>Manage Social Accounts</h1>
        <p>Connect your social media accounts to start scheduling posts.</p>

        <div class="card mb-4">
            <div class="card-header">Connect New Account</div>
            <div class="card-body">
                <a href="../app/connect_facebook.php" class="btn btn-primary">Connect Facebook</a>
                <a href="../app/connect_twitter.php" class="btn btn-secondary">Connect Twitter (X)</a>
                <!-- Add more platforms as needed -->
            </div>
        </div>

        <div class="card">
            <div class="card-header">Connected Accounts</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Platform</th>
                            <th>Username</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($accounts as $account): ?>
                            <tr>
                                <td><?php echo ucfirst(htmlspecialchars($account['platform'])); ?></td>
                                <td><?php echo htmlspecialchars($account['username']); ?></td>
                                <td>
                                    <a href="../app/disconnect_social.php?id=<?php echo $account['id']; ?>" class="btn btn-sm btn-danger">Disconnect</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
