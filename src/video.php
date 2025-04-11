<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/include/config.php';
use Eirb\Vost\Web\Models;

$videoId = null;
if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
  $videoId = (int)$_GET['id'];
} else {
  http_response_code(400);
  exit(0);
}

$req = $database->prepare('SELECT * FROM `videos` WHERE `id` = ? LIMIT 1;');
$req->execute(array($videoId));

$v = $req->fetch();

if ($v === FALSE) {
  http_response_code(404);
  exit(0);
}

$video = new Models\Video($v);
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="icon" type="image/png" href="/assets/static/favicon.png">
    <link rel="stylesheet" href="/assets/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,500..700;1,100..900&family=Roboto:ital,wght@0,400..700;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="/assets/main.js"></script>
    <title><?= $video->getTitle(); ?> • VOST - Club vidéo de l'Enseirb-Matmeca</title>
  </head>
  <body id="home-body">
    <?php include __DIR__ . "/include/header.php"; ?>
    <main>
			<div class="section">
				<h2><?= $video->getTitle(); ?></h2>
				<div class="video">
          <?php if ($session->isLoggedIn()) {
            ?>
            <iframe id="player" type="text/html" src="<?= $video->getEmbedUrl(); ?>" frameborder="0" allow="autoplay"></iframe>
            <?php
          } else {
            ?>
            <div id="player" class="login-required" style="background-image: url('<?= $video->getThumbnail(); ?>');">
              <div class="login-overlay">
                <h3>Vidéo privée</h3>
                <p>Connectez-vous pour la regarder</p>
                <div class="button-bar-outlined">
                  <a href="/protect/login.php?redirect=<?= urlencode($_SERVER['REQUEST_URI']); ?>" class="button-outlined">
                    <span>Se connecter</span>
                  </a>
                </div>
              </div>
            </div>
            <?php
          } ?>
				</div>
        <div class="info-container">
          <div class="credits">
            <p>Publié le <?= $video->formatPublishedOn(); ?></p>
          </div>
          <?php if ($session->isLoggedIn()) { ?>
          <div class="view-on-platform button-bar">
            <a href="<?= $video->getUrl(); ?>" class="button">
              <span>Voir sur <?= $video->getPlatformName(); ?></span>
            </a>
          </div>
          <?php } ?>
        </div>
			</div>
		</main>
    <?php include __DIR__ . '/include/footer.php'; ?>
  </body>
</html>
