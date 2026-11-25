<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check($_POST['csrf'] ?? '')) {
    $_SESSION = [];
    session_destroy();
}
header('Location: login.php');
exit;
