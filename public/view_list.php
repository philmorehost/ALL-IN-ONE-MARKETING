<?php
require_once '../app/functions.php';
if (!is_logged_in()) {
    redirect('login.php');
}
$user_id = $_SESSION['user_id'];
$list = null;
if (isset($_GET['id'])) {
    $list_id = $_GET['id'];
    $listStmt = pdo()->prepare("SELECT * FROM contact_lists WHERE id = ? AND user_id = ?");
    $listStmt->execute([$list_id, $user_id]);
    $list = $listStmt->fetch();

    $contactsStmt = pdo()->prepare("SELECT * FROM contacts WHERE list_id = ?");
    $contactsStmt->execute([$list_id]);
    $contacts = $contactsStmt->fetchAll();
}
if (!$list) {
    redirect('email_marketing.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View List</title>
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
                </ul>
            </div>
            <a href="../app/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>
    <div class="container mt-5">
        <h1><?php echo htmlspecialchars($list['name']); ?></h1>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Add New Contact</div>
                    <div class="card-body">
                        <form action="../app/add_contact.php" method="post">
                            <?php require_once '../app/csrf.php'; echo csrf_input(); ?>
                            <input type="hidden" name="list_id" value="<?php echo $list['id']; ?>">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Contact</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Contacts</div>
                    <div class="card-body">
                        <table class="table">
                            <thead><tr><th>Email</th><th>Actions</th></tr></thead>
                            <tbody>
                                <?php foreach ($contacts as $contact): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($contact['email']); ?></td>
                                    <td><a href="../app/delete_contact.php?id=<?php echo $contact['id']; ?>" class="btn btn-sm btn-danger">Delete</a></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
