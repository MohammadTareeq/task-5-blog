<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function get_pdo(): PDO {
    $host = '127.0.0.1';
    $db   = 'blog';
    $user = 'root';   // update if needed
    $pass = '';       // update if needed
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

    $opts = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    return new PDO($dsn, $user, $pass, $opts);
}

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
