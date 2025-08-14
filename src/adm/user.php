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

// Get user information
$req = $database->prepare('SELECT * FROM `users` WHERE `id` = ? LIMIT 1;');
$req->execute(array($id));
$u = $req->fetch();

if (!isset($u['id'])) {
  http_response_code(404);
  exit();
}

$user = new Models\User($u);

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
    <title><?= $user->getName(); ?> • AdmVOST - Club vidéo de l'Enseirb-Matmeca</title>
  </head>
  <body id="home-body">
    <?php include __DIR__ . "/../include/header.php"; ?>
    <main id="adm-home">
      <div class="section">
        <h2>Gestion membre • <?= $user->getName(); ?></h2>
        <div class="button-bar section-content multi-section">
          <a href="/adm/year.php?id=<?= $user->year; ?>" class="button">
            <span><i class="fa-solid fa-arrow-left"></i>Retour</span>
          </a>
        </div>
        <form class="section-content multi-section user-form" action="actions/edit-user.php" method="GET">
          <input type="number" name="id" value="<?= $id; ?>" style="display: none;">
          <div class="input-labeled">
            <label for="name">Nom</label>
            <input type="text" name="name" value="<?= $user->getName(); ?>">
          </div>
          <div class="input-labeled">
            <label for="role">Rôle</label>
            <input type="text" name="role" value="<?= $user->getRole(); ?>">
          </div>
          <div class="input-labeled">
            <label for="uid">Identifiant CAS</label>
            <input type="text" name="uid" value="<?= htmlspecialchars($user->uid); ?>">
          </div>
          <div class="danger-card">
            <b><i class="fa-solid fa-circle-exclamation"></i>Attention !</b>
            <p>
              Modifier l'identifiant CAS d'un membre peut lui bloquer l'accès admin.
            </p>
            <p>
              Pour éviter de vous bloquer vous-même, ne modifiez jamais votre propre identifiant CAS.
            </p>
          </div>
          <div class="button-bar">
            <button type="submit">Enregistrer</button>
          </div>
        </form>
      </div>
      <div class="section">
        <h2 id="thumb">Photo de profil</h2>
        <div class="section-content multi-section">
          <?php
          if ($user->profilePicture == null) {
            ?>
            <div class="info-card">
              <b><i class="fa-solid fa-circle-info"></i>Aucune photo pour ce membre</b>
              <p>
                Importez une photo au format carré de taille minimale 256&times;256 px.
              </p>
              <p>
                Max 1 Mo. Types acceptés : JPEG, PNG, WEBP.
              </p>
            </div>
            <form action="actions/add-user-picture.php?id=<?= $id; ?>" method="POST" enctype="multipart/form-data">
              <div class="button-bar">
                <label class="button" for="picture"><i class="fa-solid fa-upload"></i>Importer une photo</label>
              </div>
              <input type="file" id="picture" name="picture" accept="image/png, image/jpeg, image/webp" style="display: none;" onchange="this.parentElement.submit();" />
            </form>
            <?php
          } else {
            ?>
            <img src="/assets/users/<?= $user->getProfilePicture(); ?>" alt="Photo de profil" class="user-picture-preview">
            <div class="button-bar">
              <a href="actions/del-user-picture.php?id=<?= $id; ?>" class="button button-danger">
                <span><i class="fa-solid fa-trash"></i>Supprimer la photo</span>
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
          <div class="danger-card">
            <b><i class="fa-solid fa-circle-exclamation"></i>Attention !</b>
            <p>
              Ces actions sont à effet immédiat.
            </p>
            <p>
              Pour éviter de vous bloquer vous-même, ne désactivez jamais votre propre accès admin / ne supprimez jamais votre propre compte.
            </p>
          </div>
          <div class="button-bar">
            <a href="actions/toggle-user.php?id=<?= $id; ?>" class="button">
              <span><i class="fa-solid fa-user<?= ($user->isActive) ? "-xmark" : ""; ?>"></i><?= ($user->isActive) ? "Désactiver" : "Activer"; ?> l'accès admin</span>
            </a>
            <a href="actions/del-user.php?id=<?= $id; ?>&year=<?= $user->year; ?>" class="button button-danger">
              <span><i class="fa-solid fa-trash"></i>Supprimer l'utilisateur</span>
            </a>
          </div>
        </div>
      </div>
    </main>
    <?php include __DIR__ . '/../include/footer.php'; ?>
  </body>
</html>
