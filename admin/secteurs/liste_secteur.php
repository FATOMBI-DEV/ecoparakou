  <?php
    session_start();
    
    $page_title = "Gestion des secteurs";
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

    if (!in_array($role, ['admin', 'moderateur'])) {
      echo "Accès refusé.";
      exit;
    }

    // Récupération des secteurs avec nombre d'entreprises
    $query = "
      SELECT s.id, s.nom, s.slug, s.ordre, COUNT(e.id) AS nb_entreprises
      FROM secteurs s
      LEFT JOIN entreprises e ON e.secteur_id = s.id
      GROUP BY s.id
      ORDER BY s.ordre ASC, s.nom ASC
    ";
    $secteurs = $mysqli->query($query);
  ?>

  <!DOCTYPE html>
  <html lang="fr">
    <head>
      <?php include_once '../../includes/meta-head.php'; ?>
      <link rel="stylesheet" href="../../public/assets/css/liste_secteur.css">
      <link rel="stylesheet" href="../../public/assets/css/header.css">
      <link rel="stylesheet" href="../../public/assets/css/footer.css">
    </head>
  
    <body>
      <?php include_once '../../includes/header.php'; ?>

      <div class="container py-5">
        <?php if (isset($_GET['success'])): ?>
          <div class="alert alert-success">✅ Secteur supprimé avec succès.</div>
        <?php endif; ?>

        <h2 class="mb-4">
          <i class="bi bi-folder me-2"></i> Gestion des secteurs
        </h2>

        <?php if (in_array($role, ['admin', 'moderateur'])): ?>
          <a href="ajouter_secteur.php" class="btn btn-accent mb-3">➕ Ajouter un secteur</a>
        <?php endif; ?>

        <table class="table table-bordered table-hover align-middle">
          <thead>
            <tr>
              <th>Nom</th>
              <th>Slug</th>
              <th>Ordre</th>
              <th>Entreprises</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($s = $secteurs->fetch_assoc()): ?>
              <tr id="secteur-row-<?= $s['id'] ?>">
                <td><?= htmlspecialchars($s['nom']) ?></td>
                <td><?= htmlspecialchars($s['slug']) ?></td>
                <td><?= $s['ordre'] ?></td>
                <td><span class="badge bg-secondary"><?= $s['nb_entreprises'] ?></span></td>
                <td>
                  <a href="modifier_secteur.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
                  <?php if ($role === 'admin'): ?>
                    <button class="btn btn-sm btn-outline-danger ms-1"
                      onclick="confirmerSuppression(<?= $s['id'] ?>, '<?= addslashes($s['nom']) ?>', <?= $s['nb_entreprises'] ?>)">
                      Supprimer
                    </button>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <!-- Modal de confirmation -->
      <div id="confirmationModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content shadow">
            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title">⚠️ Confirmation suppression</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
              <p id="modalMessage"></p>
              <div class="d-flex justify-content-center gap-3 mt-3">
                <button id="confirmDeleteBtn" class="btn btn-danger">Oui, supprimer</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php include_once '../../includes/footer.php'; ?>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

      <script>
        let secteurIdToDelete = null;
        const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));

        function confirmerSuppression(id, nom, nb) {
          secteurIdToDelete = id;
          document.getElementById('modalMessage').innerHTML =
            `Le secteur <strong>${nom}</strong> contient <strong>${nb}</strong> entreprise(s).<br>Voulez-vous vraiment le supprimer ? Cette action est <u>irréversible</u>.`;
          modal.show();
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
          fetch('supprimer_secteur.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${secteurIdToDelete}`
          })
          .then(res => {
            if (!res.ok) throw new Error("Erreur HTTP " + res.status);
            return res.text();
          })
          .then(data => {
            if (data === 'success') {
              modal.hide();
              const row = document.getElementById(`secteur-row-${secteurIdToDelete}`);
              row.classList.add('fade-out');
              setTimeout(() => row.remove(), 500);
            } else {
              alert("Erreur : " + data);
            }
          })
          .catch(err => {
            alert("Échec de la requête : " + err.message);
          });
        });
      </script>
    </body>
  </html>