<?php
require_once __DIR__ . '/config.php';

$error = '';
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!csrf_check($_POST['csrf'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } elseif (too_many_attempts($pdo, $username, $ip)) {
        $error = 'Too many failed attempts. Please try again in 10 minutes.';
    } else {
        $stmt = $pdo->prepare('SELECT id, username, password_hash FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            log_attempt($pdo, $username, $ip, true);
            session_regenerate_id(true); // prevents session fixation
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: dashboard.php');
            exit;
        }
        log_attempt($pdo, $username, $ip, false);
        $error = 'Invalid username or password.'; // same message for both cases
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Login</title></head>
<body>
<h1>Login</h1>
<?php if (isset($_GET['registered'])): ?><p style="color:green">Registration successful. Please log in.</p><?php endif; ?>
<?php if ($error): ?><p style="color:red"><?= e($error) ?></p><?php endif; ?>
<form method="post" action="login.php">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <p>Username: <input type="text" name="username" required></p>
    <p>Password: <input type="password" name="password" required></p>
    <p><button type="submit">Login</button></p>
</form>
<p>No account? <a href="register.php">Register</a></p>
</body>
</html>
