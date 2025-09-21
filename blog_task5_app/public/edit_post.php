<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin','editor']);

$pdo = get_pdo();
$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=?");
$stmt->execute([$id]);
$post = $stmt->fetch();
if (!$post) { header("Location: index.php?msg=Not+found"); exit; }
if (!can_edit_post($post)) { header("Location: unauthorized.php"); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    if ($title===''||$content==='') $errors[]="Both fields required.";
    if (!$errors) {
        $stmt = $pdo->prepare("UPDATE posts SET title=?, content=?, updated_at=NOW() WHERE id=?");
        $stmt->execute([$title,$content,$id]);
        header("Location: index.php?msg=Updated");
        exit;
    }
}

include __DIR__ . '/../includes/header.php';
?>
<div class="card p-4">
  <h4>Edit Post</h4>
  <?php if ($errors): ?><div class="alert alert-danger"><?php echo e(implode('<br>', $errors)); ?></div><?php endif; ?>
  <form method="post">
    <div class="mb-3"><label>Title</label><input name="title" class="form-control" value="<?php echo e($post['title']); ?>"></div>
    <div class="mb-3"><label>Content</label><textarea name="content" class="form-control" rows="6"><?php echo e($post['content']); ?></textarea></div>
    <button class="btn btn-primary">Update</button>
  </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
