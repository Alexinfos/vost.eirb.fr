<header class="header">
  <div class="header-bar">
    <img class="header-brand" src="/assets/static/logo-full.svg" alt="Logo VOST">
    <nav class="header-nav">
      <a class="header-nav-item" href="/">Accueil</a>
      <a class="header-nav-item" href="/videos.php">Vidéos</a>
      <a class="header-nav-item" href="/equipe.php">L'équipe</a>
    </nav>
  </div>
  <div class="header-toolbar">
    <a class="header-toolbar-item toolbar-item-disabled" href="#"><i class="fa-solid fa-user"></i><?= $session->getDisplayName(); ?></a>
    <?php if ($session->isLoggedIn()) {
      ?>
      <a class="header-toolbar-item" href="/protect/logout.php?redirect=<?= urlencode($_SERVER['REQUEST_URI']); ?>"><i class="fa-solid fa-right-from-bracket"></i>Déconnexion</a>
      <?php
    } else {
      ?>
      <a class="header-toolbar-item" href="/protect/login.php?redirect=<?= urlencode($_SERVER['REQUEST_URI']); ?>"><i class="fa-solid fa-right-to-bracket"></i>Connexion</a>
      <?php
    } ?>
  </div>
</header>