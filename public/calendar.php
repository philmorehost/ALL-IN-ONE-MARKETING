<?php
require_once '../app/functions.php';
if (!is_logged_in()) {
    redirect('login.php');
}
$user_id = $_SESSION['user_id'];
$postsStmt = pdo()->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY scheduled_at DESC");
$postsStmt->execute([$user_id]);
$posts = $postsStmt->fetchAll();

$accountsStmt = pdo()->prepare("SELECT * FROM social_accounts WHERE user_id = ?");
$accountsStmt->execute([$user_id]);
$accounts = $accountsStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Calendar</title>
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
        <h1>Content Calendar</h1>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createPostModal">Create Post</button>

        <div class="card">
            <div class="card-header">Scheduled Posts</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Content</th>
                            <th>Scheduled At</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 100))); ?>...</td>
                            <td><?php echo date('F j, Y, g:i a', strtotime($post['scheduled_at'])); ?></td>
                            <td><?php echo ucfirst($post['status']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Post Modal -->
    <div class="modal fade" id="createPostModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="../app/create_post.php" method="post">
                        <?php require_once '../app/csrf.php'; echo csrf_input(); ?>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Publish to:</label>
                            <?php foreach ($accounts as $account): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="accounts[]" value="<?php echo $account['id']; ?>" id="account_<?php echo $account['id']; ?>">
                                <label class="form-check-label" for="account_<?php echo $account['id']; ?>">
                                    <?php echo ucfirst($account['platform']); ?> - <?php echo htmlspecialchars($account['username']); ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mb-3">
                            <label for="scheduled_at" class="form-label">Scheduled Time (Optional)</label>
                            <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at">
                        </div>
                        <button type="submit" class="btn btn-primary">Schedule Post</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
