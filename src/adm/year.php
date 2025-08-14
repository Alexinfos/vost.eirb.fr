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

// Get year information
$req = $database->prepare('SELECT * FROM `years` WHERE `id` = ? LIMIT 1;');
$req->execute(array($id));
$y = $req->fetch();

if (!isset($y['id'])) {
  http_response_code(404);
  exit();
}

$year = new Models\Year($y);

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
    <title>Mandat <?= $year->getName(); ?> • AdmVOST - Club vidéo de l'Enseirb-Matmeca</title>
  </head>
  <body id="home-body">
    <?php include __DIR__ . "/../include/header.php"; ?>
    <main id="adm-home">
      <div class="section">
        <h2>Gestion mandat • <?= $year->getName(); ?></h2>
        <div class="button-bar section-content multi-section">
          <a href="/adm/" class="button">
            <span><i class="fa-solid fa-arrow-left"></i>Retour</span>
          </a>
        </div>
      </div>
      <div class="section">
        <h2>Membres</h2>
        <div class="section-content multi-section">
          <table>
            <tr>
              <th>N°</th>
              <th>Nom <i class="fa-solid fa-arrow-down-a-z sort-hint" title="Trié par ordre alphabétique"></i></th>
              <th>Rôle</th>
              <th>Identifiant CAS</th>
              <th>Accès admin ?</th>
              <th><!-- Modifier --></th>
            </tr>
            <?php foreach ($year->getUsers($database) as $user) {
              ?>
              <tr>
                <td><?= $user->id; ?></td>
                <td><?= $user->getName(); ?></td>
                <td><?= $user->getRole(); ?></td>
                <td><?= htmlspecialchars($user->uid); ?></td>
                <td><i class="fa-solid fa-<?= ($user->isActive) ? "check" : "xmark"; ?>"></i></td>
                <td><a href="user.php?id=<?= $user->id; ?>"><i class="fa-solid fa-pencil"></i></a></td>
              </tr>
              <?php
            } ?>
          </table>
          <div class="button-bar">
            <a href="actions/new-user.php?year=<?= $id; ?>" class="button">
              <span><i class="fa-solid fa-plus"></i>Nouveau membre</span>
            </a>
          </div>
        </div>
      </div>
      <div class="section">
        <h2>Photo de groupe</h2>
        <div class="section-content multi-section">
          <?php
          if ($year->groupPicture == null) {
            ?>
            <div class="info-card">
              <b><i class="fa-solid fa-circle-info"></i>Aucune photo pour ce mandat</b>
              <p>
                Importez une photo au format 16/9 de taille minimale 720&times;405 px.
              </p>
              <p>
                Max 1 Mo. Types acceptés : JPEG, PNG, WEBP.
              </p>
            </div>
            <form action="actions/add-year-picture.php?id=<?= $id; ?>" method="POST" enctype="multipart/form-data">
              <div class="button-bar">
                <label class="button" for="picture"><i class="fa-solid fa-upload"></i>Importer une image</label>
              </div>
              <input type="file" id="picture" name="picture" accept="image/png, image/jpeg, image/webp" style="display: none;" onchange="this.parentElement.submit();" />
            </form>
            <?php
          } else {
            ?>
            <img src="/assets/years/<?= $year->getGroupPicture(); ?>" alt="Photo de groupe" class="thumbnail-preview">
            <div class="button-bar">
              <a href="actions/del-year-picture.php?id=<?= $id; ?>" class="button button-danger">
                <span><i class="fa-solid fa-trash"></i>Supprimer la photo</span>
              </a>
            </div>
            <?php
          }
          ?>
        </div>
      </div>
    </main>
    <?php include __DIR__ . '/../include/footer.php'; ?>
  </body>
</html>
