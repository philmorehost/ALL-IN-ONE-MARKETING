<?php
require_once '../../app/admin/admin_functions.php';
admin_only();

$user = null;
if (isset($_GET['id'])) {
    $stmt = pdo()->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $user = $stmt->fetch();
}

if (!$user) {
    redirect('dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card w-50 mx-auto">
            <div class="card-header">
                <h1>Edit User</h1>
            </div>
            <div class="card-body">
                <?php require_once '../../app/csrf.php'; ?>
                <form action="../../app/admin/edit_user.php" method="post">
                    <?php echo csrf_input(); ?>
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="is_admin" class="form-label">Admin</label>
                        <select class="form-select" id="is_admin" name="is_admin">
                            <option value="1" <?php echo $user['is_admin'] ? 'selected' : ''; ?>>Yes</option>
                            <option value="0" <?php echo !$user['is_admin'] ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
