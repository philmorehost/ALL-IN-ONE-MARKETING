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
    <title>Email Marketing</title>
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
        <h1>Email Marketing Suite</h1>

        <ul class="nav nav-tabs" id="emailTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="campaigns-tab" data-bs-toggle="tab" data-bs-target="#campaigns" type="button" role="tab">Campaigns</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="lists-tab" data-bs-toggle="tab" data-bs-target="#lists" type="button" role="tab">Contact Lists</button>
            </li>
        </ul>

        <div class="tab-content" id="emailTabsContent">
            <!-- Campaigns Tab -->
            <div class="tab-pane fade show active" id="campaigns" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Campaigns</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCampaignModal">Create Campaign</button>
                </div>
                <table class="table mt-3">
                    <thead><tr><th>Subject</th><th>List</th><th>Status</th><th>Sent At</th></tr></thead>
                    <tbody>
                        <?php
                        $campaignsStmt = pdo()->prepare(
                            "SELECT c.*, cl.name as list_name FROM campaigns c JOIN contact_lists cl ON c.list_id = cl.id WHERE c.user_id = ?"
                        );
                        $campaignsStmt->execute([$user_id]);
                        $campaigns = $campaignsStmt->fetchAll();
                        foreach ($campaigns as $campaign):
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($campaign['subject']); ?></td>
                            <td><?php echo htmlspecialchars($campaign['list_name']); ?></td>
                            <td><?php echo ucfirst($campaign['status']); ?></td>
                            <td><?php echo $campaign['sent_at'] ? date('F j, Y, g:i a', strtotime($campaign['sent_at'])) : 'N/A'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Contact Lists Tab -->
            <div class="tab-pane fade" id="lists" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Contact Lists</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createListModal">Create List</button>
                </div>
                <table class="table mt-3">
                    <thead><tr><th>Name</th><th>Contacts</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php
                        $user_id = $_SESSION['user_id'];
                        $listsStmt = pdo()->prepare("SELECT cl.*, COUNT(c.id) as contact_count FROM contact_lists cl LEFT JOIN contacts c ON cl.id = c.list_id WHERE cl.user_id = ? GROUP BY cl.id");
                        $listsStmt->execute([$user_id]);
                        $lists = $listsStmt->fetchAll();
                        foreach ($lists as $list):
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($list['name']); ?></td>
                            <td><?php echo $list['contact_count']; ?></td>
                            <td>
                                <a href="view_list.php?id=<?php echo $list['id']; ?>" class="btn btn-sm btn-info">View</a>
                                <a href="../app/delete_list.php?id=<?php echo $list['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Campaign Modal -->
    <div class="modal fade" id="createCampaignModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Campaign</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="../app/create_campaign.php" method="post">
                        <?php echo csrf_input(); ?>
                        <div class="mb-3">
                            <label for="list_id" class="form-label">Select Contact List</label>
                            <select class="form-select" id="list_id" name="list_id" required>
                                <?php foreach ($lists as $list): ?>
                                <option value="<?php echo $list['id']; ?>"><?php echo htmlspecialchars($list['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="body" class="form-label">Email Body</label>
                            <textarea class="form-control" id="body" name="body" rows="10" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Campaign</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Create List Modal -->
    <div class="modal fade" id="createListModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Contact List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="../app/create_list.php" method="post">
                        <?php require_once '../app/csrf.php'; echo csrf_input(); ?>
                        <div class="mb-3">
                            <label for="list_name" class="form-label">List Name</label>
                            <input type="text" class="form-control" id="list_name" name="name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create List</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
