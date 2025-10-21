<?php
require_once '../app/functions.php';

$page = null;
if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];
    $pageStmt = pdo()->prepare("SELECT * FROM landing_pages WHERE slug = ?");
    $pageStmt->execute([$slug]);
    $page = $pageStmt->fetch();

    if ($page) {
        $componentsStmt = pdo()->prepare("SELECT * FROM landing_page_components WHERE page_id = ? ORDER BY sort_order ASC");
        $componentsStmt->execute([$page['id']]);
        $components = $componentsStmt->fetchAll();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page ? htmlspecialchars($page['title']) : 'Page Not Found'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .page-container { max-width: 600px; margin: auto; }
    </style>
</head>
<body>
    <div class="container mt-5 page-container">
        <?php if ($page): ?>
            <h1 class="text-center mb-4"><?php echo htmlspecialchars($page['title']); ?></h1>
            <?php foreach ($components as $component): ?>
                <div class="mb-3">
                    <?php if ($component['type'] === 'text'): ?>
                        <p class="text-center"><?php echo nl2br(htmlspecialchars($component['content'])); ?></p>
                    <?php elseif ($component['type'] === 'link'):
                        $link = json_decode($component['content'], true); ?>
                        <a href="<?php echo htmlspecialchars($link['url']); ?>" class="btn btn-primary w-100 p-3"><?php echo htmlspecialchars($link['text']); ?></a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-danger">Page not found.</div>
        <?php endif; ?>
    </div>
</body>
</html>
