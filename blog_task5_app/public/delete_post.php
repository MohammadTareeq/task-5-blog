<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin']);

$pdo = get_pdo();
$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("DELETE FROM posts WHERE id=?");
$stmt->execute([$id]);
header("Location: index.php?msg=Deleted");
exit;
