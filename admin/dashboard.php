<?php
include_once '../includes/session.php';
include_once '../includes/meta-head.php';
include_once '../includes/header.php';
?>

<div class="container mt-5">
  <h2>Tableau de bord</h2>
  <p>Bienvenue <?= $_SESSION['user_nom'] ?? 'Utilisateur' ?>.</p>
  <ul>
    <li><a href="/admin/entreprises/liste.php">Entreprises à valider</a></li>
    <li><a href="/admin/utilisateurs/liste.php">Gestion des membres</a></li>
    <li><a href="/admin/logs/historique.php">Historique des actions</a></li>
  </ul>
</div>

<?php include_once '../includes/footer.php'; ?>