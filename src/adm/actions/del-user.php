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

if (!isset($_GET['id']) || !isset($_GET['year'])) {
    http_response_code(422);
    exit();
}

// Delete in database
$req = $database->prepare('DELETE FROM `users` WHERE `id` = ?;');
$req->execute([(int)$_GET['id']]);

header('Location: /adm/year.php?id=' . $_GET['year']);
?>