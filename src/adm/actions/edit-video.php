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
    || !isset($_GET['url']) || !filter_var(filter_var($_GET['url'], \FILTER_SANITIZE_URL), \FILTER_VALIDATE_URL)
    || !isset($_GET['publishedOn'])
    || !isset($_GET['year']) || !ctype_digit($_GET['year'])
    || !isset($_GET['duration-hours']) || !ctype_digit($_GET['duration-hours'])
    || !isset($_GET['duration-minutes']) || !ctype_digit($_GET['duration-minutes'])
    || !isset($_GET['duration-seconds']) || !ctype_digit($_GET['duration-seconds']))
{
    http_response_code(422);
    exit();
}

$title = $_GET['title'];
$url = filter_var($_GET['url'], \FILTER_SANITIZE_URL);
$publishedOn = strtotime($_GET['publishedOn']) * 1000;
$year = (int)$_GET['year'];
$duration = (int)$_GET['duration-hours'] * 3600 + (int)$_GET['duration-minutes'] * 60 + (int)$_GET['duration-seconds'];

// Convert youtu.be URLs to youtube.com/watch
if (str_contains($url, "youtu.be")) {
    $video_id = explode("?", explode("youtu.be/", $url)[1])[0];
    $url = "https://www.youtube.com/watch?v=" . $video_id;
}

// Sanitize youtube URLs (remove tracking, playlist, etc.)
if (str_contains($url, "youtube.com") && str_contains($url, "&")) {
    $parsedUrl = parse_url($url);
    parse_str($parsedUrl['query'], $query);
    $url = "https://www.youtube.com/watch?v=" . $query['v'];
}

// Update database
$req = $database->prepare('UPDATE `videos` SET `title` = ?, `url` = ?, `publishedOn` = ?, `year` = ?, `duration` = ? WHERE `id` = ?;');
$req->execute([$title, $url, $publishedOn, $year, $duration, (int)$_GET['id']]);

header('Location: /adm/video.php?id=' . (int)$_GET['id']);
?>