<?php
require_once __DIR__ . '/../includes/config.php';
$pdo = get_pdo();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    if (strlen($username) < 3) $errors[] = "Username must be at least 3 chars.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 chars.";
    if ($password !== $confirm) $errors[] = "Passwords do not match.";

    if (!$errors) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username=?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $errors[] = "Username already taken.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username,password,role) VALUES (?,?,?)");
            $stmt->execute([$username,$hash,'user']);
            header("Location: login.php?msg=Registered+successfully");
            exit;
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>
<style>
  /* Corrected CSS to remove the hand cursor from buttons and links */
  body,
  a,
  button,
  .btn,
  .btn-primary,
  .page-link {
    cursor: default;
  }
</style>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4">
            <h4>Register</h4>
            <?php if ($errors): ?><div class="alert alert-danger"><?php echo e(implode('<br>', $errors)); ?></div><?php endif; ?>
            <form method="post">
                <div class="mb-3"><label>Username</label><input name="username" class="form-control" required></div>
                <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                <div class="mb-3"><label>Confirm</label><input type="password" name="confirm" class="form-control" required></div>
                <button class="btn btn-primary">Register</button>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>