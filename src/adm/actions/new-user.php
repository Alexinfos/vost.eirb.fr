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

if (!isset($_GET['year'])) {
    http_response_code(422);
    exit();
}

// Insert new row in database
$req = $database->prepare('INSERT INTO `users` (`name`, `role`, `uid`, `profilePicture`, `year`, `isActive`) VALUES ("Nouveau membre", "Membre", NULL, NULL, ?, 0);');
$req->execute([(int)$_GET['year']]);
$newId = $database->lastInsertId();

header('Location: /adm/user.php?id=' . $newId);
?>