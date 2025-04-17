<?php
require_once __DIR__ . '/../include/config.php';
use Eirb\Vost\Web\Models;

if (!$session->isLoggedIn()) {
    header('Location: /protect/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

if (!$session->isAdmin()) {
    http_response_code(403);
    exit();
}

function getVideos(\PDO $database): array {
  $req = $database->prepare('
    SELECT `videos`.* FROM `videos`
    ORDER BY `id` DESC;
  ');
  $req->execute();

  $videoList = array();
  while ($v = $req->fetch()) {
    $video = new Models\Video($v);
    array_push($videoList, $video);
  }

  return $videoList;
}

function getYears(\PDO $database): array {
  $req = $database->prepare('
    SELECT `years`.*, COUNT(`users`.`id`) AS `memberCount` FROM `years`
    LEFT OUTER JOIN `users` ON `years`.`id` = `users`.`year`
    GROUP BY `years`.`id`
    ORDER BY `name` DESC;
  ');
  $req->execute();

  $yearsList = array();
  while ($y = $req->fetch()) {
    $year = new Models\Year($y);
    array_push($yearsList, $year);
  }

  return $yearsList;
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
    <title>AdmVOST - Club vidéo de l'Enseirb-Matmeca</title>
  </head>
  <body id="home-body">
    <?php include __DIR__ . "/../include/header.php"; ?>
    <main id="adm-home">
      <div class="section">
        <h2>AdmVOST</h2>
        <div class="button-bar section-content">
          <a href="#" target="_blank" class="button">
            <span><i class="fa-solid fa-book"></i>Manuel d'utilisation</span>
          </a>
          <a href="https://github.com/Alexinfos/vost.eirb.fr" target="_blank" class="button">
            <span><i class="fa-brands fa-github"></i>Code source</span>
          </a>
        </div>
      </div>
      <div class="section">
        <h2>Vidéos</h2>
        <div class="section-content">
          <table>
            <tr>
              <th>N°</th>
              <th><!-- Visibilité --></th>
              <th>Titre</th>
              <th>Date de publication</th>
              <th><!-- Modifier --></th>
            </tr>
            <?php foreach (getVideos($database) as $video) {
              ?>
              <tr>
                <td><?= $video->id; ?></td>
                <td title="<?= ($video->visible == 1) ? 'Vidéo visible sur le site' : 'Vidéo masquée sur le site (brouillon)'; ?>" class="help"><i class="fa-solid <?= ($video->visible == 1) ? 'fa-eye' : 'fa-eye-slash'; ?>"></i></td>
                <td><?= $video->getTitle(); ?></td>
                <td><?= $video->formatPublishedOn(); ?></td>
                <td><a href="video.php?id=<?= $video->id; ?>"><i class="fa-solid fa-pencil"></i></a></td>
              </tr>
              <?php
            } ?>
          </table>
          <div class="button-bar">
            <a href="actions/new-video.php" target="_blank" class="button">
              <span><i class="fa-solid fa-plus"></i>Nouvelle vidéo</span>
            </a>
          </div>
        </div>
      </div>
      <div class="section">
        <h2>Mandats</h2>
        <div class="section-content">
          <table>
            <tr>
              <th>N°</th>
              <th>Nom</th>
              <th>Nombre de membres</th>
              <th><!-- Modifier --></th>
            </tr>
            <?php foreach (getYears($database) as $year) {
              ?>
              <tr>
                <td><?= $year->id; ?></td>
                <td><?= $year->getName(); ?></td>
                <td><?= $year->memberCount; ?></td>
                <td><a href="video.php?id=<?= $video->id; ?>"><i class="fa-solid fa-pencil"></i></a></td>
              </tr>
              <?php
            } ?>
          </table>
          <div class="button-bar">
            <a href="actions/new-team.php" target="_blank" class="button">
              <span><i class="fa-solid fa-plus"></i>Nouveau mandat</span>
            </a>
          </div>
        </div>
      </div>
    </main>
    <?php include __DIR__ . '/../include/footer.php'; ?>
  </body>
</html>
