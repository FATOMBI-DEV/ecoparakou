  <?php
    session_start();
    include_once '../../includes/db.php';
    include_once '../../includes/fonctions.php';
    $page_title = "Modifier utilisateur";

    $success = '';
    $error = '';

    if (!isset($_SESSION['admin_id'])) {
      header("Location: ../login.php");
      exit;
    }

    $admin_id = $_SESSION['admin_id'];
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id <= 0) {
      echo "ID invalide.";
      exit;
    }

    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nom = trim($_POST['nom']);
      $email = trim($_POST['email']);
      $role = $_POST['role'];

      // Vérifier unicité email (hors utilisateur actuel)
      $stmt = $mysqli->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ? AND id != ?");
      $stmt->bind_param("si", $email, $id);
      $stmt->execute();
      $stmt->bind_result($count);
      $stmt->fetch();
      $stmt->close();

      if ($count > 0) {
        $error = "Cet email est déjà utilisé.";
      } else {
        $stmt = $mysqli->prepare("UPDATE utilisateurs SET nom = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nom, $email, $role, $id);
        
        if ($stmt->execute()) {
          log_action($admin_id, "Modification de l'utilisateur", "utilisateurs", $id);
          header("Location: liste_utilisateur.php?success=" . urlencode("✅ Utilisateur modifié."));
          exit;
        } else {
          $error = "Erreur lors de la modification : " . $stmt->error;
        }
        $stmt->close();
      }
    }

    // Récupération des données
    $stmt = $mysqli->prepare("SELECT nom, email, role FROM utilisateurs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($nom, $email, $role);
    $stmt->fetch();
    $stmt->close();
  ?>
  <!DOCTYPE html>
  <html lang="fr">
    <head>
      <?php include_once '../../includes/meta-head.php'; ?>
      <link rel="stylesheet" href="../../public/assets/css/ajouter_utilisateur.css">
      <link rel="stylesheet" href="../../public/assets/css/header.css">
      <link rel="stylesheet" href="../../public/assets/css/footer.css">
    </head>
    <body>
      <?php include_once '../../includes/header.php'; ?>
      <main>
        <div class="form-box">
          <h2>Modifier utilisateur</h2>
          <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>
          <form method="POST" class="form-ajout-utilisateur">
            <input type="text" name="nom" value="<?= htmlspecialchars($nom) ?>" required>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
            <select name="role" required>
              <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Administrateur</option>
              <option value="moderateur" <?= $role === 'moderateur' ? 'selected' : '' ?>>Modérateur</option>
            </select>
            <button type="submit">Enregistrer les modifications</button>
          </form>
        </div>
      </main>

      <?php include_once '../../includes/footer.php'; ?>    
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
  </html>