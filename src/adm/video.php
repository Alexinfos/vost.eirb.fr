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

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
  http_response_code(400);
  exit();
}

$id = (int)$_GET['id'];

// Get video information
$req = $database->prepare('SELECT * FROM `videos` WHERE `id` = ? LIMIT 1;');
$req->execute(array($id));
$v = $req->fetch();

if (!isset($v['id'])) {
  http_response_code(404);
  exit();
}

$video = new Models\Video($v);

// Get all years
function getYears(\PDO $database): array {
  $req = $database->prepare('SELECT * FROM `years` ORDER BY `name` DESC;');
  $req->execute();
  
  $years = array();
  while ($y = $req->fetch()) {
    $year = new Models\Year($y);
    array_push($years, $year);
  }

  return $years;
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
    <title><?= $video->getTitle(); ?> • AdmVOST - Club vidéo de l'Enseirb-Matmeca</title>
  </head>
  <body id="home-body">
    <?php include __DIR__ . "/../include/header.php"; ?>
    <main id="adm-home">
      <div class="section">
        <h2>Gestion vidéo • <?= $video->getTitle(); ?></h2>
        <div class="button-bar section-content multi-section">
          <a href="/adm/" class="button">
            <span><i class="fa-solid fa-arrow-left"></i>Retour</span>
          </a>
        </div>
        <form class="section-content multi-section video-form" action="actions/edit-video.php" method="GET">
          <input type="number" name="id" value="<?= $id; ?>" style="display: none;">
          <div class="input-labeled">
            <label for="title">Titre</label>
            <input type="text" name="title" value="<?= $video->getTitle(); ?>">
          </div>
          <div class="input-labeled">
            <label for="url">URL</label>
            <input type="text" name="url" value="<?= $video->getURL(); ?>">
          </div>
          <div class="input-labeled">
            <label for="publishedOn">Date de publication</label>
            <input type="date" name="publishedOn" value="<?= $video->getPublishedOn(); ?>">
          </div>
          <div class="input-labeled">
            <label for="year">Année</label>
            <select name="year">
              <?php foreach (getYears($database) as $year) {
                ?>
                <option value="<?= $year->id; ?>" <?= ($year->id == $video->year) ? "selected" : ""; ?>><?= $year->getName(); ?></option>
                <?php
              } ?>
            </select>
          </div>
          <div class="input-labeled">
            <label for="duration-hours">Durée</label>
            <div class="multi-input">
              <input type="number" name="duration-hours" value="<?= $video->getDurationHours(); ?>" min=0 hint="hh" class="text-align-right">
              <p>h</p>
              <input type="number" name="duration-minutes" value="<?= $video->getDurationMinutes(); ?>" min=0 hint="mm" class="text-align-right">
              <p>min</p>
              <input type="number" name="duration-seconds" value="<?= $video->getDurationSeconds(); ?>" min=0 hint="ss" class="text-align-right">
              <p>s</p>
            </div>
          </div>
          <div class="button-bar">
            <button type="submit">Enregistrer</button>
          </div>
        </form>
      </div>
      <div class="section">
        <h2 id="thumb">Miniature</h2>
        <div class="section-content multi-section">
          <?php
          if ($video->thumbnail == null) {
            ?>
            <div class="info-card">
              <b><i class="fa-solid fa-circle-info"></i>Aucune miniature pour cette vidéo</b>
              <p>
                Importez une miniature au format 16/9 de taille minimale 720&times;405 px.
              </p>
              <p>
                Max 1 Mo. Types acceptés : JPEG, PNG, WEBP.
              </p>
            </div>
            <form action="actions/add-video-thumb.php?id=<?= $id; ?>" method="POST" enctype="multipart/form-data">
              <div class="button-bar">
                <label class="button" for="thumbnail"><i class="fa-solid fa-upload"></i>Importer une image</label>
              </div>
              <input type="file" id="thumbnail" name="thumbnail" accept="image/png, image/jpeg, image/webp" style="display: none;" onchange="this.parentElement.submit();" />
            </form>
            <?php
          } else {
            ?>
            <img src="<?= $video->getThumbnail(); ?>" alt="Miniature" class="thumbnail-preview">
            <div class="button-bar">
              <a href="actions/del-video-thumb.php?id=<?= $id; ?>" class="button button-danger">
                <span><i class="fa-solid fa-trash"></i>Supprimer la miniature</span>
              </a>
            </div>
            <?php
          }
          ?>
        </div>
      </div>
      <div class="section">
        <h2 id="other">Actions</h2>
        <div class="section-content multi-section">
          <div class="button-bar">
            <a href="actions/toggle-video.php?id=<?= $id; ?>" class="button">
              <span><i class="fa-solid fa-eye<?= ($video->visible) ? "-slash" : ""; ?>"></i>Rendre la vidéo <?= ($video->visible) ? "in" : ""; ?>visible</span>
            </a>
            <?php /*<a href="actions/del-video.php?id=<?= $id; ?>" class="button button-danger">
              <span><i class="fa-solid fa-trash"></i>Supprimer la vidéo</span>
            </a>*/ ?>
          </div>
        </div>
      </div>
    </main>
    <?php include __DIR__ . '/../include/footer.php'; ?>
  </body>
</html>
