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
    <title>AI Content Assistant</title>
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
                </ul>
            </div>
            <a href="../app/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>
    <div class="container mt-5">
        <h1>AI Content Generation Assistant</h1>
        <p>Generate social media captions, email subject lines, and more with the power of AI.</p>

        <div class="card">
            <div class="card-body">
                <?php require_once '../app/csrf.php'; ?>
                <form action="../app/generate_ai_content.php" method="post">
                    <?php echo csrf_input(); ?>
                    <div class="mb-3">
                        <label for="topic" class="form-label">What is the topic?</label>
                        <input type="text" class="form-control" id="topic" name="topic" required>
                    </div>
                    <div class="mb-3">
                        <label for="content_type" class="form-label">What type of content do you need?</label>
                        <select class="form-select" id="content_type" name="content_type">
                            <option value="social_caption">Social Media Caption</option>
                            <option value="email_subject">Email Subject Line</option>
                            <option value="ad_copy">Ad Copy</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Generate Content</button>
                </form>
            </div>
        </div>

        <?php if (isset($_SESSION['ai_content'])): ?>
        <div class="card mt-4">
            <div class="card-header">Generated Content</div>
            <div class="card-body">
                <pre><?php echo htmlspecialchars($_SESSION['ai_content']); unset($_SESSION['ai_content']); ?></pre>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
