<?php
require_once __DIR__ . '/config.php';

$errors = [];
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf'] ?? '')) {
        $errors[] = 'Invalid request. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';

        // Server-side validation
        if (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $username)) {
            $errors[] = 'Username must be 3-30 characters: letters, numbers, underscore.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }
        if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must be at least 8 characters and contain letters and numbers.';
        }
        if ($password !== $confirm) {
            $errors[] = 'Passwords do not match.';
        }

        if (!$errors) {
            // Prepared statement prevents SQL injection
            $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $errors[] = 'Username or email is already registered.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT); // never store plain passwords
                $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)');
                $stmt->execute([$username, $email, $hash]);
                header('Location: login.php?registered=1');
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Register</title></head>
<body>
<h1>Register</h1>
<?php foreach ($errors as $err): ?>
    <p style="color:red"><?= e($err) ?></p>
<?php endforeach; ?>
<form method="post" action="register.php">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <p>Username: <input type="text" name="username" value="<?= e($username) ?>" required></p>
    <p>Email: <input type="email" name="email" value="<?= e($email) ?>" required></p>
    <p>Password: <input type="password" name="password" required></p>
    <p>Confirm password: <input type="password" name="confirm" required></p>
    <p><button type="submit">Register</button></p>
</form>
<p>Already registered? <a href="login.php">Login</a></p>
</body>
</html>
