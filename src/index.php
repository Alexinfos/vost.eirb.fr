<?php
require_once __DIR__ . '/include/config.php';
use Eirb\Vost\Web\Models;

function getHomeSections(\PDO $database): array {
  $req = $database->prepare('SELECT * FROM `home_sections`;');
  $req->execute();

  $sectionList = array();
  while ($s = $req->fetch()) {
    $section = new Models\HomeSection($s);
    array_push($sectionList, $section);
  }

  return $sectionList;
}
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
    <!-- SEO / Embeds -->
    <meta name="description" content="VOST est le club vidéo de l'ENSEIRB-MATMECA, une école d'ingénieur de Bordeaux INP.">
    <meta name="keywords" content="VOST, club, vidéo, enseirb, matmeca, eirb, Bordeaux, INP">
    <meta property="og:url" content="https://vost.eirb.fr/">
    <meta property="og:type" content="website">
    <meta property="og:description" content="VOST est le club vidéo de l'ENSEIRB-MATMECA, une école d'ingénieur de Bordeaux INP.">
    <meta property="og:image" content="https://vost.eirb.fr/assets/static/logo-colored.png">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="VOST - Club vidéo de l'Enseirb-Matmeca">
    <meta name="twitter:description" content="VOST est le club vidéo de l'ENSEIRB-MATMECA, une école d'ingénieur de Bordeaux INP.">
    <meta name="twitter:image" content="https://vost.eirb.fr/assets/static/logo-colored.png">
    <!-- -->
    <title>VOST - Club vidéo de l'Enseirb-Matmeca</title>
  </head>
  <body id="home-body">
    <?php include __DIR__ . "/include/header.php"; ?>
    <div id="hero-section">
      <video autoplay muted loop poster="/assets/static/hero.webp">
        <source src="/assets/static/hero.mp4" type="video/mp4">
      </video>
      <div id="hero-content">
        <img src="/assets/static/logo-colored.svg">
        <h1>Club vidéo de l'Enseirb-Matmeca</h1>
        <div class="button-bar-outlined">
          <a href="/videos.php" class="button-outlined"><span>Nos vidéos</span></a>
          <a href="/equipe.php" class="button-outlined"><span>Nous connaître</span></a>
        </div>
      </div>
    </div>
    <main id="presentation">
      <?php foreach (getHomeSections($database) as $section) {
        ?>
        <div class="section">
          <h2><?= $section->getTitle(); ?></h2>
          <div class="section-content">
            <?= $section->getContent(); ?>
          </div>
        </div>
        <?php
      }
      ?>
      <div class="section">
        <h2>Nous contacter</h2>
        <div class="button-bar section-content">
          <a href="https://www.youtube.com/@Clubvost" target="_blank" class="button">
            <span><i class="fa-brands fa-youtube"></i>YouTube</span>
          </a>
          <a href="https://www.instagram.com/clubvost/" target="_blank" class="button">
            <span><i class="fa-brands fa-instagram"></i>Instagram</span>
          </a>
          <a href="/protect/link.php?name=telegram" target="_blank" class="button">
            <span><i class="fa-brands fa-telegram"></i>Telegram</span>
          </a>
        </div>
      </div>
    </main>
    <?php include __DIR__ . '/include/footer.php'; ?>
  </body>
</html>
