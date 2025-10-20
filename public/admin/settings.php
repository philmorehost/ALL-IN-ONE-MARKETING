<?php
require_once '../../app/admin/admin_functions.php';
admin_only();
require_once '../../config/api_keys.php';
require_once '../../app/csrf.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="plans.php">Plans</a></li>
                    <li class="nav-item"><a class="nav-link" href="payments.php">Payments</a></li>
                    <li class="nav-item"><a class="nav-link" href="settings.php">Settings</a></li>
                </ul>
            </div>
            <a href="../../app/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>
    <div class="container mt-5">
        <h1>System Settings</h1>
        <div class="card">
            <div class="card-body">
                <form action="../../app/admin/update_settings.php" method="post">
                    <?php echo csrf_input(); ?>

                    <h4>Google OAuth</h4>
                    <div class="mb-3">
                        <label for="google_client_id" class="form-label">Client ID</label>
                        <input type="text" class="form-control" id="google_client_id" name="GOOGLE_CLIENT_ID" value="<?php echo htmlspecialchars(GOOGLE_CLIENT_ID); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="google_client_secret" class="form-label">Client Secret</label>
                        <input type="password" class="form-control" id="google_client_secret" name="GOOGLE_CLIENT_SECRET" value="<?php echo htmlspecialchars(GOOGLE_CLIENT_SECRET); ?>">
                    </div>

                    <hr>
                    <h4>Paystack</h4>
                    <div class="mb-3">
                        <label for="paystack_public_key" class="form-label">Public Key</label>
                        <input type="text" class="form-control" id="paystack_public_key" name="PAYSTACK_PUBLIC_KEY" value="<?php echo htmlspecialchars(PAYSTACK_PUBLIC_KEY ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="paystack_secret_key" class="form-label">Secret Key</label>
                        <input type="password" class="form-control" id="paystack_secret_key" name="PAYSTACK_SECRET_KEY" value="<?php echo htmlspecialchars(PAYSTACK_SECRET_KEY); ?>">
                    </div>

                    <hr>
                    <h4>Generative AI</h4>
                    <div class="mb-3">
                        <label for="ai_api_key" class="form-label">API Key</label>
                        <input type="password" class="form-control" id="ai_api_key" name="AI_API_KEY" value="<?php echo htmlspecialchars(AI_API_KEY); ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
