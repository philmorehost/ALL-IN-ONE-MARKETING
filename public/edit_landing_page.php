<?php
require_once '../app/functions.php';
if (!is_logged_in()) {
    redirect('login.php');
}
$user_id = $_SESSION['user_id'];
$page = null;
if (isset($_GET['id'])) {
    $page_id = $_GET['id'];
    $pageStmt = pdo()->prepare("SELECT * FROM landing_pages WHERE id = ? AND user_id = ?");
    $pageStmt->execute([$page_id, $user_id]);
    $page = $pageStmt->fetch();

    $componentsStmt = pdo()->prepare("SELECT * FROM landing_page_components WHERE page_id = ? ORDER BY sort_order ASC");
    $componentsStmt->execute([$page_id]);
    $components = $componentsStmt->fetchAll();
}
if (!$page) {
    redirect('landing_pages.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Landing Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Editing: <?php echo htmlspecialchars($page['title']); ?></h1>
        <p>Public URL: <a href="view_page.php?slug=<?php echo $page['slug']; ?>" target="_blank">/view_page.php?slug=<?php echo $page['slug']; ?></a></p>

        <div class="row">
            <!-- Component Forms -->
            <div class="col-md-4">
                <h4>Add New Component</h4>
                <!-- Text Component -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>Text Block</h5>
                        <form action="../app/add_component.php" method="post">
                            <?php require_once '../app/csrf.php'; echo csrf_input(); ?>
                            <input type="hidden" name="page_id" value="<?php echo $page['id']; ?>">
                            <input type="hidden" name="type" value="text">
                            <textarea name="content" class="form-control" rows="3" required></textarea>
                            <button type="submit" class="btn btn-primary mt-2">Add Text</button>
                        </form>
                    </div>
                </div>
                <!-- Link Component -->
                <div class="card">
                    <div class="card-body">
                        <h5>Link Button</h5>
                        <form action="../app/add_component.php" method="post">
                            <?php echo csrf_input(); ?>
                            <input type="hidden" name="page_id" value="<?php echo $page['id']; ?>">
                            <input type="hidden" name="type" value="link">
                            <input type="text" name="content[text]" class="form-control mb-2" placeholder="Button Text" required>
                            <input type="url" name="content[url]" class="form-control" placeholder="https://example.com" required>
                            <button type="submit" class="btn btn-primary mt-2">Add Link</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Page Preview -->
            <div class="col-md-8">
                <h4>Page Preview</h4>
                <div class="card">
                    <div class="card-body">
                        <?php foreach ($components as $component): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <?php if ($component['type'] === 'text'): ?>
                                    <p><?php echo nl2br(htmlspecialchars($component['content'])); ?></p>
                                <?php elseif ($component['type'] === 'link'):
                                    $link = json_decode($component['content'], true); ?>
                                    <a href="<?php echo htmlspecialchars($link['url']); ?>" class="btn btn-secondary w-100"><?php echo htmlspecialchars($link['text']); ?></a>
                                <?php endif; ?>
                                <a href="../app/delete_component.php?id=<?php echo $component['id']; ?>" class="btn-close ms-2"></a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
