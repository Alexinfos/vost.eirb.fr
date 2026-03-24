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
    || !isset($_GET['title'])
    || !isset($_GET['content']))
{
    http_response_code(422);
    exit();
}

$title = $_GET['title'];
$content = $_GET['content'];

// Update database
$req = $database->prepare('UPDATE `home_sections` SET `title` = ?, `content` = ? WHERE `id` = ?;');
$req->execute([$title, $content, (int)$_GET['id']]);

header('Location: /adm/home.php#section' . (int)$_GET['id']);
?>