<?php
require_once '../app/functions.php';
if (!is_logged_in()) {
    redirect('login.php');
}
$user_id = $_SESSION['user_id'];
$campaignsStmt = pdo()->prepare(
    "SELECT s.*, cl.name as list_name FROM sms_campaigns s JOIN contact_lists cl ON s.list_id = cl.id WHERE s.user_id = ? ORDER BY s.created_at DESC"
);
$campaignsStmt->execute([$user_id]);
$campaigns = $campaignsStmt->fetchAll();

$listsStmt = pdo()->prepare("SELECT * FROM contact_lists WHERE user_id = ?");
$listsStmt->execute([$user_id]);
$lists = $listsStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Marketing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Home</a></li>
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
        <h1>SMS Marketing</h1>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createSmsModal">Create SMS Campaign</button>

        <div class="card">
            <div class="card-header">SMS Campaigns</div>
            <div class="card-body">
                <table class="table">
                    <thead><tr><th>Message</th><th>List</th><th>Status</th><th>Sent At</th></tr></thead>
                    <tbody>
                        <?php foreach ($campaigns as $campaign): ?>
                        <tr>
                            <td><?php echo htmlspecialchars(substr($campaign['message'], 0, 100)); ?>...</td>
                            <td><?php echo htmlspecialchars($campaign['list_name']); ?></td>
                            <td><?php echo ucfirst($campaign['status']); ?></td>
                            <td><?php echo $campaign['sent_at'] ? date('F j, Y, g:i a', strtotime($campaign['sent_at'])) : 'N/A'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create SMS Campaign Modal -->
    <div class="modal fade" id="createSmsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New SMS Campaign</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="../app/create_sms_campaign.php" method="post">
                        <?php require_once '../app/csrf.php'; echo csrf_input(); ?>
                        <div class="mb-3">
                            <label for="list_id" class="form-label">Select Contact List</label>
                            <select class="form-select" name="list_id" required>
                                <?php foreach ($lists as $list): ?>
                                <option value="<?php echo $list['id']; ?>"><?php echo htmlspecialchars($list['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="sender_id" class="form-label">Sender ID (max 11 chars)</label>
                            <input type="text" class="form-control" name="sender_id" maxlength="11" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Campaign</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
