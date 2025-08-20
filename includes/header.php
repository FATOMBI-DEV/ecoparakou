<?php
include_once __DIR__ . '/db.php';
include_once __DIR__ . '/constants.php';

$secteurs_menu = $mysqli->query("SELECT nom, slug FROM secteurs ORDER BY ordre");
?>

<header class="navbar navbar-expand-lg bg-dark text-light fixed-top shadow-sm py-2">
  <div class="container-fluid">

    <!-- Logo -->
    <a href="/ecoparakou/public/index.php" class="navbar-brand d-flex align-items-center text-light gap-2">
      <img src="/public/assets/img/logo.svg" alt="Logo" height="32">
      <span><?= SITE_NAME ?></span>
    </a>

    <!-- Toggle mobile -->
    <button class="navbar-toggler border-0 text-light" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Menu mobile">
      <i class="bi bi-list fs-3"></i>
    </button>

    <!-- Navigation -->
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-3">

        <li class="nav-item">
          <a class="nav-link text-light" href="/ecoparakou/public/index.php">Accueil</a>
        </li>

        <!-- Dropdown Secteurs -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-light" href="/ecoparakou/public/secteur.php" id="secteursDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Secteurs
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="secteursDropdown">
            <?php while ($s = $secteurs_menu->fetch_assoc()): ?>
              <li>
                <a class="dropdown-item" href="/ecoparakou/public/secteur.php?slug=<?= htmlspecialchars($s['slug'], ENT_QUOTES) ?>">
                  <?= htmlspecialchars($s['nom'], ENT_QUOTES) ?>
                </a>
              </li>
            <?php endwhile; ?>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link btn btn-warning text-white fw-bold px-3" href="/ecoparakou/public/inscrit_entreprise.php">Inscrire</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-light" href="/ecoparakou/public/contact.php">Contact</a>
        </li>

        <!-- Barre de recherche -->
        <li class="nav-item">
          <form class="d-flex" action="/ecoparakou/public/recherche.php" method="get" role="search" aria-label="Barre de recherche">
            <input class="form-control me-2" type="search" name="q" placeholder="Rechercher..." aria-label="Rechercher">
            <button class="btn btn-outline-light" type="submit"><i class="bi bi-search"></i></button>
          </form>
        </li>

      </ul>
    </div>
  </div>
</header>