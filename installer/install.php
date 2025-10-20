<?php
session_start();
$step = isset($_GET['step']) ? (int)$_GET['step'] : 0;

if ($step === 2) {
    $_SESSION['db'] = [
        'host' => $_POST['db_host'],
        'name' => $_POST['db_name'],
        'user' => $_POST['db_user'],
        'pass' => $_POST['db_pass']
    ];

    try {
        $dsn = "mysql:host={$_SESSION['db']['host']};dbname={$_SESSION['db']['name']}";
        $pdo = new PDO($dsn, $_SESSION['db']['user'], $_SESSION['db']['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        header('Location: index.php?step=3');
        exit;
    } catch (PDOException $e) {
        $_SESSION['db_error'] = 'Database connection failed: ' . $e->getMessage();
        header('Location: index.php?step=2');
        exit;
    }
} elseif ($step === 3) {
    try {
        $dsn = "mysql:host={$_SESSION['db']['host']};dbname={$_SESSION['db']['name']}";
        $pdo = new PDO($dsn, $_SESSION['db']['user'], $_SESSION['db']['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = file_get_contents('schema.sql');
        $pdo->exec($sql);

        $name = $_POST['admin_name'];
        $email = $_POST['admin_email'];
        $password = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, 1)");
        $stmt->execute([$name, $email, $password]);

        // Create database configuration file
        $db_config = "<?php
define('DB_HOST', '{$_SESSION['db']['host']}');
define('DB_NAME', '{$_SESSION['db']['name']}');
define('DB_USER', '{$_SESSION['db']['user']}');
define('DB_PASS', '{$_SESSION['db']['pass']}');
";
        file_put_contents('../config/database.php', $db_config);

        // Update main config file
        $main_config = "<?php
require_once 'database.php';
define('INSTALLED', true);
";
        file_put_contents('../config/config.php', $main_config);

        header('Location: index.php?step=4');
        exit;
    } catch (Exception $e) {
        $_SESSION['admin_error'] = 'Error: ' . $e->getMessage();
        header('Location: index.php?step=3');
        exit;
    }
}
