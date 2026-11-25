<?php
// Database connection (XAMPP defaults: user root, empty password)
$dsn = 'mysql:host=localhost;dbname=secure_login;charset=utf8mb4';
$pdo = new PDO($dsn, 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);

// Safer session cookies
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

require_once __DIR__ . '/functions.php';
