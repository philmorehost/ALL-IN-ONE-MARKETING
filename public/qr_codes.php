<?php
require_once '../app/functions.php';
if (!is_logged_in()) {
    redirect('login.php');
}
$user_id = $_SESSION['user_id'];
$codesStmt = pdo()->prepare("SELECT * FROM qr_codes WHERE user_id = ?");
$codesStmt->execute([$user_id]);
$codes = $codesStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Codes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="qr_codes.php">QR Codes</a></li>
                </ul>
            </div>
            <a href="../app/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>
    <div class="container mt-5">
        <h1>QR Code Generator</h1>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createQrModal">Create New QR Code</button>

        <div class="row">
            <?php foreach ($codes as $code): ?>
            <div class="col-md-4">
                <div class="card">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo urlencode('http://localhost:8000/app/track_qr.php?id=' . $code['id']); ?>" class="card-img-top mx-auto mt-3" alt="QR Code" style="width: 150px;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($code['name']); ?></h5>
                        <p class="card-text">URL: <?php echo htmlspecialchars($code['url']); ?></p>
                        <p class="card-text">Scans: <?php echo $code['scan_count']; ?></p>
                        <a href="../app/delete_qr_code.php?id=<?php echo $code['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Create QR Code Modal -->
    <div class="modal fade" id="createQrModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="../app/create_qr_code.php" method="post">
                        <?php require_once '../app/csrf.php'; echo csrf_input(); ?>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="url" class="form-label">URL</label>
                            <input type="url" class="form-control" id="url" name="url" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create QR Code</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
