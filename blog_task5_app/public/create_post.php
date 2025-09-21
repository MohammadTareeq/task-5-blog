<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin','editor']);

$pdo = get_pdo();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    if ($title === '' || $content === '') $errors[] = "Title & content required.";
    if (!$errors) {
        $stmt = $pdo->prepare("INSERT INTO posts (user_id,title,content) VALUES (?,?,?)");
        $stmt->execute([$_SESSION['user_id'],$title,$content]);
        header("Location: index.php?msg=Post+created");
        exit;
    }
}

include __DIR__ . '/../includes/header.php';
?>
<style>
  body,
  a,
  button,
  input[type="submit"],
  .btn,
  .btn-outline-light,
  .page-link {
    cursor: default;
  }
</style>
<div class="card p-4">
  <h4>Create Post</h4>
  <?php if ($errors): ?><div class="alert alert-danger"><?php echo e(implode('<br>', $errors)); ?></div><?php endif; ?>
  <form method="post">
    <div class="mb-3"><label>Title</label><input name="title" class="form-control" required></div>
    <div class="mb-3"><label>Content</label><textarea name="content" class="form-control" rows="6" required></textarea></div>
    <button class="btn btn-success">Publish</button>
  </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
