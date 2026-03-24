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

// Get home sections
$req = $database->prepare('SELECT * FROM `home_sections`;');
$req->execute();
$sections = array();
while ($s = $req->fetch()) {
  array_push($sections, new Models\HomeSection($s));
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
    <title>Gestion de l'accueil • AdmVOST - Club vidéo de l'Enseirb-Matmeca</title>
  </head>
  <body id="home-body">
    <?php include __DIR__ . "/../include/header.php"; ?>
    <main id="adm-home">
      <div class="section">
        <h2>Gestion de l'accueil</h2>
        <div class="button-bar section-content multi-section">
          <a href="/adm/" class="button">
            <span><i class="fa-solid fa-arrow-left"></i>Retour</span>
          </a>
        </div>
        <?php foreach ($sections as $sect) {
          ?>
          <h3 id="section<?= $sect->id; ?>">Section</h3>
          <form class="section-content multi-section" action="actions/edit-home-section.php" method="GET">
            <input type="number" name="id" value="<?= $sect->id; ?>" style="display: none;">
            <div class="input-labeled">
              <label for="title">Titre</label>
              <input type="text" name="title" value="<?= $sect->getTitle(); ?>">
            </div>
            <div class="input-labeled">
              <label for="content">Contenu</label>
              <textarea name="content" rows=10><?= $sect->getContent(); ?></textarea>
            </div>
            <div class="info-card">
              <b><i class="fa-solid fa-circle-info"></i>HTML Autorisé</b>
              <p>
                Vous pouvez mettre du code HTML dans ce champ. (Utilisez par exemple la balise &lt;br&gt; pour un retour à la ligne)
              </p>
              <p>
                Attention cependant à ne pas y mettre n'importe quoi et pensez bien à refermer toute balise.
              </p>
            </div>
            <div class="button-bar">
              <button type="submit">Enregistrer</button>
              <a href="actions/del-home-section.php?id=<?= $sect->id; ?>" class="button button-danger">
                <span><i class="fa-solid fa-trash"></i>Supprimer la section</span>
              </a>
            </div>
          </form>
          <?php
        }
        ?>
      </div>
      <div class="section">
        <h2 id="other">Actions</h2>
        <div class="section-content multi-section">
          <div class="button-bar">
            <a href="actions/new-home-section.php" class="button">
              <span><i class="fa-solid fa-plus"></i>Nouvelle section</span>
            </a>
          </div>
        </div>
      </div>
    </main>
    <?php include __DIR__ . '/../include/footer.php'; ?>
  </body>
</html>
