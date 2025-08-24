  <?php
    session_start();
    $page_title = "Modifier un secteur";
    include_once '../../includes/db.php';
    include_once '../../includes/fonctions.php';

    if (!isset($_SESSION['admin_id'])) {
      header("Location: ../login.php");
      exit;
    }

    // Vérification du rôle
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

    // Récupération du secteur
    $id = intval($_GET['id']);
    $stmt = $mysqli->prepare("SELECT nom, slug, description FROM secteurs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($nom_actuel, $slug_actuel, $description_actuel);
    $stmt->fetch();
    $stmt->close();

    $erreur = "";
    $success = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nom = trim($_POST['nom']);
      $description = trim($_POST['description'] ?? '');

      if (empty($nom)) {
        $erreur = "Le nom du secteur est requis.";
      } else {
        $slug = generer_slug($nom);

        $stmt = $mysqli->prepare("UPDATE secteurs SET nom = ?, slug = ?, description = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nom, $slug, $description, $id);
        $stmt->execute();
        $stmt->close();

        
        log_action($admin_id, "Modification du secteur : $nom", "secteurs", $id);
        
        $success = "Secteur modifié avec succès.";
        $nom_actuel = $nom;
        $slug_actuel = $slug;
        $description_actuel = $description;
      }
    }
  ?>

  <!DOCTYPE html>
  <html lang="fr">
    <head>
      <?php include_once '../../includes/meta-head.php'; ?>
      <link rel="stylesheet" href="../../public/assets/css/modifier_secteur.css">
      <link rel="stylesheet" href="../../public/assets/css/header.css">
      <link rel="stylesheet" href="../../public/assets/css/footer.css">
    </head>
    <body>
      <?php include_once '../../includes/header.php'; ?>
      <main>
        <div class="container py-5">
          <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
              <div class="form-wrapper p-4 rounded shadow-sm">
                <h2 class="mb-4 text-center text-primary">
                  <i class="bi bi-pencil-square me-2"></i> Modifier le secteur
                </h2>

                <?php if ($erreur): ?>
                  <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= $erreur ?>
                  </div>
                <?php elseif ($success): ?>
                  <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= $success ?>
                  </div>
                <?php endif; ?>

                <form method="POST">
                  <div class="mb-3">
                    <label for="nom" class="form-label">Nom du secteur</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="<?= htmlspecialchars($nom_actuel) ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4"><?= htmlspecialchars($description_actuel) ?></textarea>
                  </div>

                  <div class="d-flex justify-content-between mt-4">
                    <a href="liste_secteur.php" class="btn btn-secondary">
                      <i class="bi bi-arrow-left me-1"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-accent">
                      <i class="bi bi-save me-1"></i> Enregistrer
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </main>
      <?php include_once '../../includes/footer.php'; ?>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    </body>
  </html>