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

// Get latest year
$req = $database->prepare('SELECT `name` FROM `years` ORDER BY `name` DESC LIMIT 1;');
$req->execute();
$lastYearName = $req->fetchColumn();

$startYear = (int)explode("-", $lastYearName)[1];
$newYearName = $startYear . "-" . ($startYear + 1);

// Insert new row in database
$req = $database->prepare('INSERT INTO `years` (`name`, `groupPicture`) VALUES (?, NULL);');
$req->execute([$newYearName]);
$newId = $database->lastInsertId();

header('Location: /adm/year.php?id=' . $newId);
?>