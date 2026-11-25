<?php
require_once __DIR__ . '/config.php';
require_login();

// Show this user's recent login attempts (simple security monitoring)
$stmt = $pdo->prepare('SELECT ip, success, attempted_at FROM login_attempts WHERE username = ? ORDER BY id DESC LIMIT 10');
$stmt->execute([$_SESSION['username']]);
$attempts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Dashboard</title></head>
<body>
<h1>Welcome, <?= e($_SESSION['username']) ?></h1>
<h2>Recent login attempts on your account</h2>
<table border="1" cellpadding="6">
    <tr><th>Time</th><th>IP</th><th>Result</th></tr>
    <?php foreach ($attempts as $a): ?>
        <tr>
            <td><?= e($a['attempted_at']) ?></td>
            <td><?= e($a['ip']) ?></td>
            <td><?= $a['success'] ? 'Success' : 'Failed' ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<form method="post" action="logout.php">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <p><button type="submit">Logout</button></p>
</form>
</body>
</html>
