<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/include/config.php';
use Eirb\Vost\Web\Models;

function getYears(\PDO $database): array {
  $req = $database->prepare('
    SELECT `years`.* FROM `years`
    LEFT OUTER JOIN `users` ON `years`.`id` = `users`.`year`
    GROUP BY `years`.`id`
    HAVING COUNT(`users`.`id`) > 0
    ORDER BY `name` DESC;
  '); // Select only years with at least one user
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
    <title>L'équipe • VOST - Club vidéo de l'Enseirb-Matmeca</title>
  </head>
  <body id="home-body">
    <?php include __DIR__ . "/include/header.php"; ?>
    <main>
      <div class="section">
        <h2>L'équipe VOST</h2>
        <div class="teams-list">
          <?php foreach(getYears($database) as $year) {
            ?>
            <div class="team-section">
              <div class="team-hero">
                <h3>
                  <span>Mandat</span><span><?= $year->getFirstYear(); ?>&nbsp;&nbsp;<br>&nbsp;&nbsp;<?= $year->getSecondYear(); ?></span>
                </h3>
                <img src="/assets/years/<?= $year->getGroupPicture(); ?>" alt="Équipe <?= $year->getName(); ?>" loading="lazy" />
              </div>
              <div class="team-members">
                <?php foreach ($year->getUsers($database) as $user) {
                  ?>
                  <div class="user-card">
                    <img src="/assets/users/<?= $user->getProfilePicture(); ?>" alt="Photo de profil de <?= $user->getName(); ?>" loading="lazy" />
                    <h4><?= $user->getName(); ?></h4>
                    <?php if (isset($user->role)) { ?>
                      <p><?= $user->getRole(); ?></p>
                    <?php } ?>
                  </div>
                  <?php
                } ?>
              </div>
            </div>
            <?php
          } ?>
        </div>
      </div>
		</main>
    <?php include __DIR__ . '/include/footer.php'; ?>
  </body>
</html>
