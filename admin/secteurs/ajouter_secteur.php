  <?php
    session_start();
    $page_title = "Ajout-Secteur";
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

    $erreur = "";
    $success = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nom = trim($_POST['nom']);

      $res = $mysqli->query("SELECT MAX(ordre) FROM secteurs");
      $max = $res->fetch_row()[0] ?? 0;
      $ordre = $max + 1;
      $description = trim($_POST['description'] ?? '');

      if (empty($nom)) {
        $erreur = "Le nom du secteur est requis.";
      } else {
        $slug = generer_slug($nom);

        $check = $mysqli->prepare("SELECT COUNT(*) FROM secteurs WHERE slug = ?");
        $check->bind_param("s", $slug);
        $check->execute();
        $check->bind_result($count);
        $check->fetch();
        $check->close();

        if ($count > 0) {
          $erreur = "Ce secteur existe déjà.";
        } else {
          $stmt = $mysqli->prepare("INSERT INTO secteurs (nom, slug, description, ordre) VALUES (?, ?, ?, ?)");
          $stmt->bind_param("sssi", $nom, $slug, $description, $ordre);

          if ($stmt->execute()) {
            $id_secteur = $stmt->insert_id;
            log_action($admin_id, "Ajout du secteur : $nom", "secteurs", $id_secteur);
            $success = "Secteur ajouté avec succès.";
          } else {
            $erreur = "Erreur lors de l'ajout du secteur.";
          }

          $stmt->close();
        }
      }
    }
  ?>

  <!DOCTYPE html>
  <html lang="fr">

    <head>
      <?php include_once '../../includes/meta-head.php'; ?>
      <link rel="stylesheet" href="../../public/assets/css/ajouter_secteur.css">
      <link rel="stylesheet" href="../../public/assets/css/header.css">
      <link rel="stylesheet" href="../../public/assets/css/footer.css">
    </head>

    <body>

      <?php include_once '../../includes/header.php'; ?>
      <main>
        <div class="container py-5">
          <h2 class="mb-4 text-primary text-center">
            <i class="bi bi-plus-circle icon-title"></i> Ajouter un secteur
          </h2>

          <?php if ($erreur): ?>
            <div class="alert alert-danger text-center"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= $erreur ?></div>
          <?php elseif ($success): ?>
            <div class="alert alert-success text-center"><i class="bi bi-check-circle-fill me-2"></i><?= $success ?></div>
          <?php endif; ?>

          <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
              <form method="POST" class="p-4 shadow-sm bg-white rounded">
                <div class="mb-3">
                  <label for="nom" class="form-label">Nom du secteur</label>
                  <input type="text" name="nom" id="nom" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label for="description" class="form-label">Description du secteur</label>
                  <textarea name="description" id="description" class="form-control" rows="3" placeholder="Décrivez brièvement ce secteur..."></textarea>
                </div>
              <div class="d-flex justify-content-between flex-wrap gap-2">
                  <a href="liste_secteur.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Retour
                  </a>
                  <button type="submit" class="btn btn-accent">
                    <i class="bi bi-plus-circle me-1"></i> Ajouter
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </main>
      <?php include_once '../../includes/footer.php'; ?>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
  </html>