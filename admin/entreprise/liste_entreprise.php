<?php
session_start();
include_once '../../includes/db.php';
include_once '../../includes/fonctions.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: ../login.php");
  exit;
}

$admin_id = $_SESSION['admin_id'];
$stmt = $mysqli->prepare("SELECT role FROM utilisateurs WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

$sql = "SELECT e.*, s.nom AS secteur_nom FROM entreprises e
        LEFT JOIN secteurs s ON e.secteur_id = s.id
        ORDER BY e.date_inscription DESC";
$res = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Liste des entreprises</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #F5F1EB; font-family: 'Poppins', sans-serif; }
    .table thead { background-color: #1F2A44; color: #fff; }
    .badge-valide { background-color: #198754; }
    .badge-rejete { background-color: #dc3545; }
    .badge-attente { background-color: #ffc107; color: #333; }
    .badge-suspendu { background-color: #6c757d; }
    .btn-accent { background-color: #FF9800; color: #fff; }
    .btn-accent:hover { background-color: #e68900; }
  </style>
</head>
<body>


  <div class="container py-5">
    <?php if (isset($_GET['success']) && $_GET['success'] === 'modifiee'): ?>
  <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>
    ✅ L’entreprise a été modifiée avec succès.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>
  <h2 class="mb-4"><i class="bi bi-building icon-title text-accent me-2"></i>Entreprises inscrites</h2>

  <table class="table table-bordered table-hover align-middle">
    <thead>
      <tr>
        <th>Nom</th>
        <th>Secteur</th>
        <th>Statut</th>
        <th>Contact</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($e = $res->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($e['nom']) ?></td>
          <td><?= htmlspecialchars($e['secteur_nom']) ?></td>
          <td>
            <?php
              $statut = $e['statut'];
              $badge = match($statut) {
                'valide' => '<span class="badge badge-valide"><i class="bi bi-check-circle me-1"></i>Validée</span>',
                'rejete' => '<span class="badge badge-rejete"><i class="bi bi-x-circle me-1"></i>Rejetée</span>',
                'suspendu' => '<span class="badge badge-suspendu"><i class="bi bi-pause-circle me-1"></i>Suspendue</span>',
                default => '<span class="badge badge-attente"><i class="bi bi-hourglass-split me-1"></i>En attente</span>',
              };
              echo $badge;
            ?>
          </td>
          <td>
            <i class="bi bi-envelope me-1"></i><?= htmlspecialchars($e['email_contact']) ?><br>
            <i class="bi bi-telephone me-1"></i><?= htmlspecialchars($e['telephone']) ?>
          </td>
          <td>
            <a href="modifier_entreprise.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Modifier">
              <i class="bi bi-pencil-square"></i>
            </a>

            <?php if (in_array($role, ['admin', 'moderateur'])): ?>
              <?php if ($statut === 'en_attente'): ?>
                <button type="button" class="btn btn-sm btn-success me-1" onclick="ouvrirModalValidation(<?= $e['id'] ?>)" title="Valider">
                  <i class="bi bi-check-circle"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger me-1" onclick="ouvrirModalRejet(<?= $e['id'] ?>)" title="Rejeter">
                  <i class="bi bi-x-circle"></i>
                </button>
              <?php elseif ($statut === 'rejete'): ?>
                <button type="button" class="btn btn-sm btn-success me-1" onclick="ouvrirModalValidation(<?= $e['id'] ?>)" title="Valider">
                  <i class="bi bi-check-circle"></i>
                </button>
              <?php endif; ?>
            <?php endif; ?>

            <?php if ($role === 'admin'): ?>
              <?php if ($statut === 'valide'): ?>
                <button type="button" class="btn btn-sm btn-warning me-1" onclick="ouvrirModalStatut(<?= $e['id'] ?>, 'valide')" title="Suspendre">
                  <i class="bi bi-pause-circle"></i>
                </button>
              <?php elseif ($statut === 'suspendu'): ?>
                <button type="button" class="btn btn-sm btn-info me-1" onclick="ouvrirModalStatut(<?= $e['id'] ?>, 'suspendu')" title="Réactiver">
                  <i class="bi bi-play-circle"></i>
                </button>
              <?php endif; ?>

              <button type="button" class="btn btn-sm btn-outline-danger" onclick="ouvrirModalSuppression(<?= $e['id'] ?>)" title="Supprimer">
                <i class="bi bi-trash"></i>
              </button>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
<!-- ✅ Modal Validation -->
<div class="modal fade" id="modalValidation" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" action="valider_entreprise.php" class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="bi bi-check-circle me-2"></i>Valider l'entreprise</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="entrepriseIdValidation">
        <p>Confirmez-vous la validation de cette entreprise ?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-success">Valider</button>
      </div>
    </form>
  </div>
</div>

<!-- ❌ Modal Rejet -->
<div class="modal fade" id="modalRejet" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" action="rejeter_entreprise.php" class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="bi bi-x-circle me-2"></i>Rejeter l'entreprise</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="entrepriseIdRejet">
        <div class="mb-3">
          <label for="motifRejet" class="form-label">Motif du rejet</label>
          <textarea name="motif" id="motifRejet" class="form-control" rows="4" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-danger">Confirmer le rejet</button>
      </div>
    </form>
  </div>
</div>

<!-- ⏸️▶️ Modal Suspension / Réactivation -->
<div class="modal fade" id="modalStatut" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" action="act_desact_entreprise.php" class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Changer le statut</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="entrepriseIdStatut">
        <div class="mb-3" id="motifContainer">
          <label for="motifStatut" class="form-label">Motif de suspension</label>
          <textarea name="motif" id="motifStatut" class="form-control" rows="4" placeholder="Expliquer la raison de la suspension..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-warning" id="btnConfirmerStatut">Confirmer</button>
      </div>
    </form>
  </div>
</div>

<!-- 🗑️ Modal Suppression -->
<div class="modal fade" id="modalSuppression" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" action="supprimer_entreprise.php" class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="bi bi-trash me-2"></i>Supprimer l'entreprise</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="entrepriseIdSuppression">
        <div class="mb-3">
          <label for="motifSuppression" class="form-label">Motif de suppression</label>
          <textarea name="motif" id="motifSuppression" class="form-control" rows="4" required placeholder="Expliquer pourquoi cette entreprise est supprimée..."></textarea>
        </div>
        <div class="alert alert-warning">
          Cette action est <strong>irréversible</strong>. Toutes les données liées seront supprimées.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-danger">Confirmer la suppression</button>
      </div>
    </form>
  </div>
</div>
<!-- Scripts Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  function ouvrirModalValidation(id) {
    document.getElementById('entrepriseIdValidation').value = id;
    new bootstrap.Modal(document.getElementById('modalValidation')).show();
  }

  function ouvrirModalRejet(id) {
    document.getElementById('entrepriseIdRejet').value = id;
    document.getElementById('motifRejet').value = '';
    new bootstrap.Modal(document.getElementById('modalRejet')).show();
  }

  function ouvrirModalStatut(id, statutActuel) {
    document.getElementById('entrepriseIdStatut').value = id;

    const motifContainer = document.getElementById('motifContainer');
    const motifField = document.getElementById('motifStatut');
    const btn = document.getElementById('btnConfirmerStatut');

    if (statutActuel === 'suspendu') {
      // Réactivation
      motifContainer.style.display = 'none';
      motifField.removeAttribute('required');
      btn.classList.remove('btn-warning');
      btn.classList.add('btn-info');
      btn.textContent = 'Réactiver';
    } else {
      // Suspension
      motifContainer.style.display = 'block';
      motifField.setAttribute('required', 'required');
      btn.classList.remove('btn-info');
      btn.classList.add('btn-warning');
      btn.textContent = 'Suspendre';
    }

    motifField.value = '';
    new bootstrap.Modal(document.getElementById('modalStatut')).show();
  }

  function ouvrirModalSuppression(id) {
    document.getElementById('entrepriseIdSuppression').value = id;
    document.getElementById('motifSuppression').value = '';
    new bootstrap.Modal(document.getElementById('modalSuppression')).show();
  }
</script>

</body>
</html>