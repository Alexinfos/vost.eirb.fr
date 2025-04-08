<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/include/config.php';
use Eirb\Vost\Web\Components;
use Eirb\Vost\Web\Models;

function getSpotlightVideos(\PDO $database): array {
  $req = $database->prepare('SELECT * FROM `videos` ORDER BY `publishedOn` DESC LIMIT 3;');
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
    SELECT `years`.* FROM `years`
    LEFT OUTER JOIN `videos` ON `years`.`id` = `videos`.`year`
    GROUP BY `years`.`id`
    HAVING COUNT(`videos`.`id`) > 0
    ORDER BY `name` DESC;
  '); // Select only years with at least one video
  $req->execute();

  $yearList = array();
  while ($y = $req->fetch()) {
    $year = new Models\Year($y);
    array_push($yearList, $year);
  }

  return $yearList;
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
    <script src="/assets/main.js"></script>
    <title>Vidéos • VOST - Club vidéo de l'Enseirb-Matmeca</title>
  </head>
  <body id="home-body">
    <?php include __DIR__ . "/include/header.php"; ?>
    <main>
			<div class="section">
				<h2>&Agrave; la Une</h2>
				<div class="video-caroussel">
          <?php foreach (getSpotlightVideos($database) as $video) {
            $videoCard = new Components\VideoCard($video);
            echo $videoCard->toString();
          } ?>
				</div>
			</div>
			<div class="section video-section">
				<h2>Toutes les vidéos</h2>
        <?php foreach (getYears($database) as $key => $year) {
          ?>
          <div class="video-subsection <?= ($key > 0) ? "subsection-folded" : ""; ?>">
            <div class="subsection-title">
              <h3><?= $year->getName(); ?></h3>
              <i class="expand-subsection fa-solid fa-circle-chevron-down"></i>
              <i class="fold-subsection fa-solid fa-circle-chevron-up"></i>
            </div>
            <div class="video-grid">
              <?php foreach(array_reverse($year->getVideos($database)) as $video) {
                $videoCard = new Components\VideoCard($video);
                echo $videoCard->toString();
              }
              ?>
            </div>
          </div>
          <?php
        } ?>
			</div>
		</main>
    <?php include __DIR__ . '/include/footer.php'; ?>
  </body>
</html>
