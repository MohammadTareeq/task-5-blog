<?php
require_once __DIR__ . '/config.php';

function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

function current_user(): ?array {
    if (!is_logged_in()) return null;
    $pdo = get_pdo();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}

function is_admin(): bool {
    $user = current_user();
    return $user && $user['role'] === 'admin';
}

function can_edit_post(array $post): bool {
    $user = current_user();
    if (!$user) return false;
    if ($user['role'] === 'admin') return true;
    if ($user['role'] === 'editor' && $post['user_id'] == $user['id']) return true;
    return false;
}

function requireRole(array $roles): void {
    if (!is_logged_in()) {
        header("Location: login.php?msg=Please+login+first");
        exit;
    }
    $user = current_user();
    if (!$user || !in_array($user['role'], $roles, true)) {
        header("Location: unauthorized.php");
        exit;
    }
}
