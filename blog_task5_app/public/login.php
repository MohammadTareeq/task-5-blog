<?php
require_once __DIR__ . '/../includes/config.php';
$pdo = get_pdo();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php?msg=Welcome+" . urlencode($user['username']));
        exit;
    } else {
        $errors[] = "Invalid credentials.";
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

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card p-4">
      <h4>Login</h4>
      <?php if ($errors): ?><div class="alert alert-danger"><?php echo e(implode('<br>', $errors)); ?></div><?php endif; ?>
      <form method="post">
        <div class="mb-3"><label>Username</label><input class="form-control" name="username" required></div>
        <div class="mb-3"><label>Password</label><input type="password" class="form-control" name="password" required></div>
        <button class="btn btn-primary no-hand">Login</button>
      </form>
      
<p class="mt-3"><a href="register.php" class="no-hand">No account? Register</a></p>    </div>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
