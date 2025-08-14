<?php
require_once __DIR__ . '/../../include/config.php';

if (!$session->isLoggedIn()) {
    header('Location: /protect/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

if (!$session->isAdmin()) {
    http_response_code(403);
    exit();
}

if (!isset($_GET['id'])) {
    http_response_code(422);
    exit();
}

// Update database
$req = $database->prepare('UPDATE `users` SET `isActive` = NOT `isActive` WHERE `id` = ?;');
$req->execute([(int)$_GET['id']]);

header('Location: /adm/user.php?id=' . (int)$_GET['id']);
?>