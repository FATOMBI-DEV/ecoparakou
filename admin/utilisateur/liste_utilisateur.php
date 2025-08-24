  <?php
    session_start();
    include_once '../../includes/db.php';
    include_once '../../includes/fonctions.php';
    $page_title = "Gestion des utilisateurs";

    if (!isset($_SESSION['admin_id'])) {
      header("Location: ../login.php");
      exit;
    }

    $admin_id = $_SESSION['admin_id'];
    $stmt = $mysqli->prepare("SELECT role FROM utilisateurs WHERE id = ? AND actif = 1");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $stmt->bind_result($role);
    $stmt->fetch();
    $stmt->close();

    if ($role !== 'admin') {
      header("Location: ../dashboard.php");
      exit;
    }

    //Récupération des utilisateurs
    $users = $mysqli->query(" SELECT id, nom, email, role, actif, date_creation, token_invitation
      FROM utilisateurs
      ORDER BY date_creation DESC
    ");
  ?>

  <!DOCTYPE html>
  <html lang="fr">
    <head>
      <?php include_once '../../includes/meta-head.php'; ?>
      <link rel="stylesheet" href="../../public/assets/css/liste_utilisateur.css">
      <link rel="stylesheet" href="../../public/assets/css/header.css">
      <link rel="stylesheet" href="../../public/assets/css/footer.css">
    </head>

    <body>
      <?php include_once '../../includes/header.php'; ?>
      <main>
        <div class="container py-5">
          <h2 class="text-center text-primary mb-4">
            <i class="bi bi-people-fill me-2"></i> Gestion des utilisateurs
          </h2>

          <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div class="d-flex justify-content-between w-100 mt-3">
              <a href="../dashboard.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> Retour
              </a>
              <a href="ajouter_utilisateur.php" class="btn btn-accent">
                <i class="bi bi-person-plus me-1"></i> Ajouter un utilisateur
              </a>
            </div>
          </div>

          <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success text-center fw-semibold">
              <?= htmlspecialchars($_GET['success']) ?>
            </div>
          <?php endif; ?>

          <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Nom</th>
                  <th>Email</th>
                  <th>Rôle</th>
                  <th>Statut</th>
                  <th>Créé le</th>
                  <th>Token d'invitation</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($u = $users->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($u['nom']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= $u['role'] === 'admin' ? '<i class="bi bi-shield-lock"></i> Admin' : '<i class="bi bi-person-gear"></i> Modérateur' ?></td>
                    <td>
                      <span class="badge <?= $u['actif'] ? 'bg-success' : 'bg-secondary' ?>">
                        <?= $u['actif'] ? 'Actif' : 'Inactif' ?>
                      </span>
                    </td>
                    <td><?= date('d/m/Y', strtotime($u['date_creation'])) ?></td>
                    <td>
                      <?php if (!empty($u['token_invitation'])): ?>
                        <code><?= htmlspecialchars($u['token_invitation']) ?></code>
                      <?php else: ?>
                        <span class="text-muted">—</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div class="d-flex flex-wrap gap-1">
                        <a href="modifier_utilisateur.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-primary" title="Modifier">
                          <i class="bi bi-pencil-square"></i>
                        </a>

                        <?php if ($u['actif']): ?>
                          <a href="toggle_activation.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-warning" title="Désactiver" onclick="return confirm('Désactiver ce compte ?')">
                            <i class="bi bi-person-dash"></i>
                          </a>
                        <?php else: ?>
                          <a href="toggle_activation.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-success" title="Activer" onclick="return confirm('Réactiver ce compte ?')">
                            <i class="bi bi-person-check"></i>
                          </a>
                        <?php endif; ?>

                        <button onclick="confirmerSuppression(<?= $u['id'] ?>, '<?= addslashes($u['nom']) ?>')" class="btn btn-sm btn-outline-danger" title="Supprimer">
                          <i class="bi bi-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </main>
      
      <script>
        function confirmerSuppression(id, nom) {
          const confirmation = confirm(` Voulez-vous vraiment supprimer l'utilisateur "${nom}" ? Cette action est irréversible.`);
          if (confirmation) {
            window.location.href = `supprimer_utilisateur.php?id=${id}`;
          }
        }

        // Activation des tooltips Bootstrap
        const tooltipTriggerList = document.querySelectorAll('[title]');
        tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
      </script>

      <?php include_once '../../includes/footer.php'; ?>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
  </html>