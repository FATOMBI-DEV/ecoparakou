<?php
$page_title = "Entreprises à modérer";
include_once '../../includes/meta-head.php';
include_once '../../includes/header.php';
include_once '../../includes/db.php';
include_once '../../includes/session.php';

$result = $mysqli->query("SELECT id, nom, slug, email_contact, statut FROM entreprises ORDER BY date_inscription DESC");
?>

<div class="container mt-5">
  <h2 class="mb-4">Entreprises à modérer</h2>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Nom</th>
        <th>Email</th>
        <th>Statut</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($e = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($e['nom']) ?></td>
          <td><?= htmlspecialchars($e['email_contact']) ?></td>
          <td><span class="badge bg-<?= $e['statut'] === 'valide' ? 'success' : ($e['statut'] === 'en_attente' ? 'warning' : 'danger') ?>">
            <?= $e['statut'] ?>
          </span></td>
          <td>
            <?php if ($e['statut'] === 'en_attente'): ?>
              <a href="valider.php?id=<?= $e['id'] ?>" class="btn btn-success btn-sm">Valider</a>
              <a href="rejeter.php?id=<?= $e['id'] ?>" class="btn btn-danger btn-sm">Rejeter</a>
            <?php else: ?>
              <a href="/entreprise.php?slug=<?= $e['slug'] ?>" class="btn btn-outline-primary btn-sm">Voir</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include_once '../../includes/footer.php'; ?>