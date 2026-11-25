<?php
// Escape output to prevent XSS
function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// CSRF protection
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_check(?string $token): bool {
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string) $token);
}

// Login attempt limiting: block after $max failed attempts in $minutes
function too_many_attempts(PDO $pdo, string $username, string $ip, int $max = 5, int $minutes = 10): bool {
    $minutes = (int) $minutes;
    $sql = "SELECT COUNT(*) FROM login_attempts
            WHERE success = 0 AND (username = ? OR ip = ?)
            AND attempted_at > (NOW() - INTERVAL $minutes MINUTE)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $ip]);
    return (int) $stmt->fetchColumn() >= $max;
}

// Security logging: store every login attempt
function log_attempt(PDO $pdo, string $username, string $ip, bool $success): void {
    $stmt = $pdo->prepare("INSERT INTO login_attempts (username, ip, success) VALUES (?, ?, ?)");
    $stmt->execute([$username, $ip, $success ? 1 : 0]);
}

function require_login(): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}
