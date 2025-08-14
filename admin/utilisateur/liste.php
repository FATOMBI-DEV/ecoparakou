<?php
$page_title = "Gestion des membres";
include_once '../../includes/meta-head.php';
include_once '../../includes/header.php';
include_once '../../includes/db.php';
include_once '../../includes/session.php';

$result = $mysqli->query("SELECT id, nom, email, role, actif, date_creation FROM utilisateurs ORDER BY date_creation DESC");
?>

<div class="container mt-5">
  <h2 class="mb-4">Membres de l’équipe</h2>
  <a href="ajouter.php" class="btn btn-primary mb-3">Ajouter un membre</a>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Nom</th>
        <th>Email</th>
        <th>Rôle</th>
        <th>Actif</th>
        <th>Créé le</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($u = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($u['nom']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><span class="badge bg-<?= $u['role'] === 'admin' ? 'primary' : 'secondary' ?>"><?= $u['role'] ?></span></td>
          <td><?= $u['actif'] ? 'Oui' : 'Non' ?></td>
          <td><?= date('d/m/Y', strtotime($u['date_creation'])) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include_once '../../includes/footer.php'; ?>