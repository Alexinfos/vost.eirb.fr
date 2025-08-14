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

// Insert new row in database
$req = $database->prepare('INSERT INTO `videos` (`title`, `thumbnail`, `publishedOn`, `year`, `duration`, `visible`) VALUES ("Nouvelle vidéo", NULL, STRFTIME("%s", "now") * 1000, (SELECT id FROM years ORDER BY name DESC LIMIT 1), 0, 0);');
$req->execute();
$newId = $database->lastInsertId();

header('Location: /adm/video.php?id=' . $newId);
?>