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

// Get picture location
$req = $database->prepare('SELECT `profilePicture` FROM `users` WHERE `id` = ?;');
$req->execute([(int)$_GET['id']]);
$picturePath = $req->fetchColumn();

// Remove picture from database
$req = $database->prepare('UPDATE `users` SET `profilePicture` = NULL WHERE `id` = ?;');
$req->execute([(int)$_GET['id']]);

// Remove file from disk
if ($picturePath != null && $picturePath != "") {
    unlink(__DIR__ . "/../../assets/users/" . $picturePath);
}

header('Location: /adm/user.php?id=' . (int)$_GET['id']);
?>