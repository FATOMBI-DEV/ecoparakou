<?php
session_start();
$page_title = "Dashboard Admin";
include_once '../includes/db.php';
include_once '../includes/fonctions.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$nom = $_SESSION['admin_nom'];
$admin_id = $_SESSION['admin_id'];

// Récupération du rôle
$stmt = $mysqli->prepare("SELECT role FROM utilisateurs WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

// Statistiques
$nb_entreprises = $mysqli->query("SELECT COUNT(*) FROM entreprises")->fetch_row()[0];
$nb_en_attente = $mysqli->query("SELECT COUNT(*) FROM entreprises WHERE statut = 'en_attente'")->fetch_row()[0];
$nb_secteurs = $mysqli->query("SELECT COUNT(*) FROM secteurs")->fetch_row()[0];
$nb_utilisateurs = $mysqli->query("SELECT COUNT(*) FROM utilisateurs")->fetch_row()[0];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <?php include_once '../includes/meta-head.php'; ?>
  
  <link rel="stylesheet" href="assets/css/header.css">
  
  
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --color-primary: #1F2A44;
      --color-accent: #FF9800;
      --color-bg: #F5F1EB;
      --color-text: #333333;
      --color-white: #ffffff;
      --color-muted: #6c757d;
      --color-hover: #f0f0f0;
      --font-main: 'Poppins', sans-serif;
    }

    body {
      font-family: var(--font-main);
      background-color: var(--color-bg);
      color: var(--color-text);
    }

    .navbar {
      background-color: var(--color-primary);
    }

    .navbar-brand, .nav-link, .logout {
      color: var(--color-white) !important;
    }

    .card {
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .btn-accent {
      background-color: var(--color-accent);
      color: var(--color-white);
    }

    .btn-accent:hover {
      background-color: #e68900;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg px-4">
  <a class="navbar-brand fw-bold" href="#">EcoParakou Admin</a>
  <div class="ms-auto">
    <span class="me-3">Bienvenue, <?= htmlspecialchars($nom) ?> (<?= $role ?>)</span>
    <a href="logout.php" class="btn btn-sm btn-outline-light">Déconnexion</a>
  </div>
</nav>

<div class="container py-5">
  <div class="row g-4">
    <div class="col-md-3">
      <div class="card shadow-sm p-3">
        <h5 class="card-title">Entreprises</h5>
        <p class="fs-3 text-accent"><?= $nb_entreprises ?></p>
        <a href="entreprise/liste_entreprise.php" class="btn btn-accent w-100">Gérer</a>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm p-3">
        <h5 class="card-title">En attente</h5>
        <p class="fs-3 text-danger"><?= $nb_en_attente ?></p>
        <a href="validation.php" class="btn btn-outline-danger w-100">Valider</a>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm p-3">
        <h5 class="card-title">Secteurs</h5>
        <p class="fs-3 text-primary"><?= $nb_secteurs ?></p>
        <a href="secteurs/liste_secteur.php" class="btn btn-outline-primary w-100">Voir</a>
      </div>
    </div>
    <?php if ($role === 'admin'): ?>
    <div class="col-md-3">
      <div class="card shadow-sm p-3">
        <h5 class="card-title">Utilisateurs</h5>
        <p class="fs-3 text-muted"><?= $nb_utilisateurs ?></p>
        <a href="utilisateur/liste_utilisateur.php" class="btn btn-outline-dark w-100">Gérer</a>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <div class="mt-5">
    <h4>Actions rapides</h4>
    <div class="d-flex flex-wrap gap-3 mt-3">
      <a href="ajouter_entreprise.php" class="btn btn-accent">➕ Ajouter une entreprise</a>
      <a href="secteurs/ajouter_secteur.php" class="btn btn-outline-primary">➕ Ajouter un secteur</a>
      <?php if ($role === 'admin'): ?>
        <a href="utilisateur/ajouter_utilisateur.php" class="btn btn-outline-dark">➕ Ajouter un utilisateur</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>