<?php
// One-time setup: creates the database and tables from schema.sql.
// Open this page once in the browser, then delete this file.
try {
    $pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    foreach (explode(';', $sql) as $statement) {
        $statement = trim($statement);
        if ($statement !== '') {
            $pdo->exec($statement);
        }
    }
    echo 'Done. The database and tables were created. ';
    echo '<a href="register.php">Go to Register</a>. ';
    echo 'You can now delete setup.php.';
} catch (PDOException $e) {
    echo 'Setup failed: ' . htmlspecialchars($e->getMessage());
}
