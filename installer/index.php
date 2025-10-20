<?php
session_start();
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation Wizard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h1>Installation Wizard</h1>
            </div>
            <div class="card-body">
                <?php if ($step === 1): ?>
                    <h2>Welcome & Pre-flight Check</h2>
                    <?php
                        $errors = [];
                        if (version_compare(PHP_VERSION, '8.0.0', '<')) {
                            $errors[] = 'PHP version 8.0.0 or higher is required.';
                        }
                        if (!extension_loaded('pdo_mysql')) {
                            $errors[] = 'The PDO_MySQL extension is not enabled.';
                        }
                        if (!extension_loaded('curl')) {
                            $errors[] = 'The cURL extension is not enabled.';
                        }

                        if (empty($errors)):
                    ?>
                        <div class="alert alert-success">Your server meets all requirements.</div>
                        <a href="?step=2" class="btn btn-primary">Next</a>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            <strong>Please fix the following issues:</strong>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                <?php elseif ($step === 2): ?>
                    <h2>Database Configuration</h2>
                    <?php if (isset($_SESSION['db_error'])): ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['db_error']; unset($_SESSION['db_error']); ?></div>
                    <?php endif; ?>
                    <form action="install.php?step=2" method="post">
                        <div class="mb-3">
                            <label for="db_host" class="form-label">Database Host</label>
                            <input type="text" class="form-control" id="db_host" name="db_host" required>
                        </div>
                        <div class="mb-3">
                            <label for="db_name" class="form-label">Database Name</label>
                            <input type="text" class="form-control" id="db_name" name="db_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="db_user" class="form-label">Database Username</label>
                            <input type="text" class="form-control" id="db_user" name="db_user" required>
                        </div>
                        <div class="mb-3">
                            <label for="db_pass" class="form-label">Database Password</label>
                            <input type="password" class="form-control" id="db_pass" name="db_pass">
                        </div>
                        <button type="submit" class="btn btn-primary">Next</button>
                    </form>
                <?php elseif ($step === 3): ?>
                    <h2>Admin Account Creation</h2>
                    <?php if (isset($_SESSION['admin_error'])): ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['admin_error']; unset($_SESSION['admin_error']); ?></div>
                    <?php endif; ?>
                    <form action="install.php?step=3" method="post">
                        <div class="mb-3">
                            <label for="admin_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="admin_name" name="admin_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="admin_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                        </div>
                        <div class="mb-3">
                            <label for="admin_password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Admin</button>
                    </form>
                <?php elseif ($step === 4): ?>
                    <h2>Installation Complete</h2>
                    <div class="alert alert-success">Congratulations! The application has been installed successfully.</div>
                    <p>
                        <a href="../index.php" class="btn btn-primary">Go to Homepage</a>
                        <a href="../admin" class="btn btn-secondary">Go to Admin Login</a>
                    </p>
                    <p>For security reasons, please delete the 'installer' directory.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
