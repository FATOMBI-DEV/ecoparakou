<?php
$page_title = "Historique des actions";
include_once '../../includes/meta-head.php';
include_once '../../includes/header.php';
include_once '../../includes/db.php';
include_once '../../includes/session.php';

$query = "
  SELECT l.*, u.nom AS utilisateur_nom
  FROM logs_actions l
  LEFT JOIN utilisateurs u ON l.utilisateur_id = u.id
  ORDER BY l.date_action DESC
  LIMIT 100
";
$result = $mysqli->query($query);
?>

<div class="container mt-5">
  <h2 class="mb-4">Historique des actions</h2>
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>Utilisateur</th>
        <th>Action</th>
        <th>Table</th>
        <th>ID cible</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($log = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($log['utilisateur_nom']) ?></td>
          <td><?= htmlspecialchars($log['action']) ?></td>
          <td><?= htmlspecialchars($log['table_cible']) ?></td>
          <td><?= htmlspecialchars($log['cible_id']) ?></td>
          <td><?= date('d/m/Y H:i', strtotime($log['date_action'])) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include_once '../../includes/footer.php'; ?>