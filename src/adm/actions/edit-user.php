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

if (!isset($_GET['id'])
    || !isset($_GET['name'])
    || !isset($_GET['role'])
    || !isset($_GET['uid']))
{
    http_response_code(422);
    exit();
}

$name = $_GET['name'];
$role = $_GET['role'];
$uid = $_GET['uid'];

// Update database
$req = $database->prepare('UPDATE `users` SET `name` = ?, `role` = ?, `uid` = ? WHERE `id` = ?;');
$req->execute([$name, $role, $uid, (int)$_GET['id']]);

header('Location: /adm/user.php?id=' . (int)$_GET['id']);
?>