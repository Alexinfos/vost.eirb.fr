<?php
require_once __DIR__ . '/include/config.php';
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
    <meta name="description" content="VOST est le club vidéo de l'ENSEIRB-MATMECA, une école d'ingénieur située de Bordeaux INP.">
    <meta name="keywords" content="VOST, club, vidéo, enseirb, matmeca, eirb, Bordeaux, INP">
    <meta property="og:url" content="https://vost.eirb.fr/">
    <meta property="og:type" content="website">
    <meta property="og:description" content="VOST est le club vidéo de l'ENSEIRB-MATMECA, une école d'ingénieur située de Bordeaux INP.">
    <meta property="og:image" content="https://vost.eirb.fr/assets/static/logo-colored.png">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="VOST - Club vidéo de l'Enseirb-Matmeca">
    <meta name="twitter:description" content="VOST est le club vidéo de l'ENSEIRB-MATMECA, une école d'ingénieur située de Bordeaux INP.">
    <meta name="twitter:image" content="https://vost.eirb.fr/assets/static/logo-colored.png">
    <!-- -->
    <title>VOST - Club vidéo de l'Enseirb-Matmeca</title>
  </head>
  <body id="home-body">
    <?php include __DIR__ . "/include/header.php"; ?>
    <div id="hero-section">
      <video autoplay muted loop poster="/assets/static/hero.webp">
        <?php /* Video hero hosted somewhere else to avoid overloading Eirbware's server */ ?>
        <source src="https://vost.alexisbn.fr/hero.mp4" type="video/mp4">
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
      <div class="section">
        <h2>Qui sommes-nous ?</h2>
        <p>
          VOST, pour <i>Vidéos Originales, Séries TV</i>, est un club regroupant depuis 2011 des étudiants de l'Enseirb-Matmeca passionnés par l'audiovisuel. Il a pour objectif de&nbsp;:
        </p>
        <ul>
          <li>Couvrir la plupart des évènements de la vie de l'école</li>
          <li>Tourner et monter de nombreuses vidéos</li>
          <li>Réaliser des vidéos pour des prestataires extérieurs</li>
        </ul>
      </div>
      <div class="section">
        <h2>Historique</h2>
        <p>
          Le club a été fondé en mars 2011 par Jean-Baptiste Bernard avec l’objectif original de promouvoir les séries. La promotion des animes est arrivée très rapidement après. Les séances de diffusion n’ont pas connu une très forte participation, mais étaient suivies par des élèves toujours satisfaits d’être venus.
          <br><br>
          En mai 2011 s’est faite l’acquisition d’un camescope, et les vidéos ont été lancées dès la rentrée. Cette fois-ci, l’attention a été beaucoup plus importante, tout d’abord en couvrant des événements de cette période, comme le concert de l’Enseirb et la pièce du club théâtre. Ensuite, de nouvelles réalisations sont apparues, incluant des vidéos originales, c’est à dire des fausses bandes-annonces, fausses pubs et parodies.
          <br><br>
          Pendant la saison 2016/2017, le club investit dans du nouveau matériel&nbsp;: une nouvelle caméra (Sony 16300) et un stabilisateur (DJI Ronin M).
          <br><br>
          Depuis 2023, VOST dispose d'un nouveau logo.
        </p>
      </div>
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
