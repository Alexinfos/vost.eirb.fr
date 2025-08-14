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

$MAX_ELEMS_PER_PAGE = 15;

// Count amount of pages
$req = $database->prepare('SELECT COUNT(`videos`.`id`) AS c FROM `videos`;');
$req->execute();
$videoCount = (int)$req->fetchColumn();
$videoMaxPage = floor(($videoCount - 1) / $MAX_ELEMS_PER_PAGE);

$req = $database->prepare('SELECT COUNT(`years`.`id`) AS c FROM `years`;');
$req->execute();
$yearCount = (int)$req->fetchColumn();
$yearMaxPage = floor(($yearCount - 1) / $MAX_ELEMS_PER_PAGE);


$videoPage = 0;
$yearPage = 0;
if (isset($_GET['vp']) && ctype_digit($_GET['vp'])) {
  $videoPage = min(max(0, (int)$_GET['vp']), $videoMaxPage);
}

if (isset($_GET['yp']) && ctype_digit($_GET['yp'])) {
  $yearPage = min(max(0, (int)$_GET['yp']), $yearMaxPage);
}

function getVideos(\PDO $database, int $pageNumber): array {
  global $MAX_ELEMS_PER_PAGE;
  $req = $database->prepare('
    SELECT `videos`.* FROM `videos`
    ORDER BY `publishedOn` DESC
    LIMIT ? OFFSET ?;
  ');
  $req->execute(array($MAX_ELEMS_PER_PAGE, $pageNumber * $MAX_ELEMS_PER_PAGE));

  $videoList = array();
  while ($v = $req->fetch()) {
    $video = new Models\Video($v);
    array_push($videoList, $video);
  }

  return $videoList;
}

function getYears(\PDO $database, int $pageNumber): array {
  global $MAX_ELEMS_PER_PAGE;
  $req = $database->prepare('
    SELECT `years`.*, COUNT(`users`.`id`) AS `memberCount` FROM `years`
    LEFT OUTER JOIN `users` ON `years`.`id` = `users`.`year`
    GROUP BY `years`.`id`
    ORDER BY `name` DESC
    LIMIT ? OFFSET ?;
  ');
  $req->execute(array($MAX_ELEMS_PER_PAGE, $pageNumber * $MAX_ELEMS_PER_PAGE));

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
        <h2 id="videos">Vidéos</h2>
        <div class="section-content multi-section">
          <table>
            <tr>
              <th>N°</th>
              <th><!-- Visibilité --></th>
              <th>Titre</th>
              <th>Date de publication <i class="fa-solid fa-arrow-up-9-1 sort-hint" title="Trié du plus récent au plus ancien"></i></th>
              <th><!-- Modifier --></th>
            </tr>
            <?php foreach (getVideos($database, $videoPage) as $video) {
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
          <div class="page-navigator">
              <?php foreach (range(0, $videoMaxPage) as $p) {
                ?>
                <a href="/adm/?vp=<?= $p; ?>&yp=<?= $yearPage; ?>#videos" class="page-option <?= ($p == $videoPage) ? "selected-page" : "" ?>">
                  <?= $p + 1; ?>
                </a>
                <?php
              } ?>
          </div>
          <div class="button-bar">
            <a href="actions/new-video.php" class="button">
              <span><i class="fa-solid fa-plus"></i>Nouvelle vidéo</span>
            </a>
          </div>
        </div>
      </div>
      <div class="section">
        <h2 id="mandats">Mandats</h2>
        <div class="section-content multi-section">
          <table>
            <tr>
              <th>N°</th>
              <th>Nom <i class="fa-solid fa-arrow-up-9-1 sort-hint" title="Trié du plus récent au plus ancien"></i></th>
              <th>Nombre de membres</th>
              <th><!-- Modifier --></th>
            </tr>
            <?php foreach (getYears($database, $yearPage) as $year) {
              ?>
              <tr>
                <td><?= $year->id; ?></td>
                <td><?= $year->getName(); ?></td>
                <td><?= $year->memberCount; ?></td>
                <td><a href="year.php?id=<?= $year->id; ?>"><i class="fa-solid fa-pencil"></i></a></td>
              </tr>
              <?php
            } ?>
          </table>
          <div class="page-navigator">
              <?php foreach (range(0, $yearMaxPage) as $p) {
                ?>
                <a href="/adm/?vp=<?= $videoPage; ?>&yp=<?= $p; ?>#mandats" class="page-option <?= ($p == $yearPage) ? "selected-page" : "" ?>">
                  <?= $p + 1; ?>
                </a>
                <?php
              } ?>
          </div>
          <div class="button-bar">
            <a href="actions/new-year.php" class="button">
              <?php
                // New year
                $previousYear = getYears($database, 0)[0];
                $newYearName = $previousYear->getSecondYear() . "-" . ((int)$previousYear->getSecondYear() + 1);
              ?>
              <span><i class="fa-solid fa-plus"></i>Créer le mandat <?= $newYearName; ?></span>
            </a>
          </div>
        </div>
      </div>
    </main>
    <?php include __DIR__ . '/../include/footer.php'; ?>
  </body>
</html>
