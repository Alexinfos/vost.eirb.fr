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

// Get thumbnail location
$req = $database->prepare('SELECT `thumbnail` FROM `videos` WHERE `id` = ?;');
$req->execute([(int)$_GET['id']]);
$thumbPath = $req->fetchColumn();

// Remove thumbnail from database
$req = $database->prepare('UPDATE `videos` SET `thumbnail` = NULL WHERE `id` = ?;');
$req->execute([(int)$_GET['id']]);

// Remove file from disk
if ($thumbPath != null && $thumbPath != "") {
    unlink(__DIR__ . "/../.." . $thumbPath);
}

header('Location: /adm/video.php?id=' . (int)$_GET['id']);
?>