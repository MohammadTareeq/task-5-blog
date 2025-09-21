<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Blog Task 5</title>

  <!-- ✅ Custom CSS -->
  <link href="../assets/css/style.css" rel="stylesheet">

  <!-- ✅ Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- ✅ Font Awesome for icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">My Blog</a>
    <ul class="navbar-nav ms-auto">
      <?php if (!empty($_SESSION['username'])): ?>
        <li class="nav-item">
          <span class="nav-link disabled"><?php echo e($_SESSION['username']); ?> (<?php echo e($_SESSION['role']); ?>)</span>
        </li>
        <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'editor'): ?>
          <li class="nav-item"><a class="nav-link" href="create_post.php">New Post</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
      <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>

<div class="container">
