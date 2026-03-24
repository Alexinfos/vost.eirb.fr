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
$req = $database->prepare('INSERT INTO `home_sections` (`title`) VALUES ("Nouvelle section");');
$req->execute();
$newId = $database->lastInsertId();

header('Location: /adm/home.php#section' . $newId);
?>