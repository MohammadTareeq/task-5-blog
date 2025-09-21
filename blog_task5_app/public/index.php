<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

$pdo = get_pdo();

// Pagination
$limit = 5;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// Search
$search = trim($_GET['q'] ?? '');
$where = '';
$params = [];

if ($search !== '') {
    $where = "WHERE p.title LIKE :q1 OR p.content LIKE :q2";
    $params[':q1'] = "%$search%";
    $params[':q2'] = "%$search%";
}

// Count total posts
$countQuery = "SELECT COUNT(*) FROM posts p $where";
$countStmt = $pdo->prepare($countQuery);
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();
$totalPages = (int)ceil($total / $limit);

// Fetch posts
$postQuery = "SELECT p.*, u.username 
             FROM posts p 
             LEFT JOIN users u ON p.user_id = u.id 
             $where 
             ORDER BY p.created_at DESC 
             LIMIT :limit OFFSET :offset";

$postStmt = $pdo->prepare($postQuery);

// Bind search params
foreach ($params as $key => $value) {
    $postStmt->bindValue($key, $value, PDO::PARAM_STR);
}

// Bind pagination
$postStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$postStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$postStmt->execute();

$posts = $postStmt->fetchAll();

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

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Blog Posts</h1>
    <form method="get" class="d-flex" style="max-width: 400px;">
        <input type="text" name="q" class="form-control me-2" placeholder="Search posts..." value="<?php echo e($search); ?>">
        <button class="btn btn-outline-light" type="submit">Search</button>
    </form>
</div>

<?php if ($search !== ''): ?>
    <div class="alert alert-info">
        Showing **<?php echo $total; ?>** results for "<?php echo e($search); ?>"
    </div>
<?php endif; ?>

<?php if (empty($posts)): ?>
    <div class="alert alert-warning">No posts found.</div>
<?php else: ?>
    <?php foreach ($posts as $post): ?>
        <div class="card bg-secondary text-light mb-4 shadow">
            <div class="card-body">
                <h4 class="card-title"><?php echo e($post['title']); ?></h4>
                <h6 class="card-subtitle mb-2 text-muted">
                    By <?php echo e($post['username'] ?? 'Unknown'); ?> on <?php echo e($post['created_at']); ?>
                </h6>
                <p class="card-text"><?php echo nl2br(e(mb_strimwidth($post['content'], 0, 300, '...'))); ?></p>

                <?php if (can_edit_post($post)): ?>
                    <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-light me-2">
                        <i class="fas fa-pen"></i> Edit
                    </a>
                <?php endif; ?>

                <?php if (is_admin()): ?>
                    <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this post?');">
                        <i class="fas fa-trash"></i> Delete
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php if ($i === $page) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?><?php if ($search) echo '&q=' . urlencode($search); ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>