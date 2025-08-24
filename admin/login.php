  <?php
    session_start();
    include_once '../includes/db.php';
    include_once '../includes/fonctions.php';
    $page_title = "Connexion Admin";
    $error = '';
    $email_prefill = '';

    // Si token présent dans l'URL
    if (isset($_GET['token'])) {
      $token = $_GET['token'];
      $stmt = $mysqli->prepare("SELECT email FROM utilisateurs WHERE token_invitation = ? AND actif = 1");
      $stmt->bind_param("s", $token);
      $stmt->execute();
      $stmt->bind_result($email_token);
      if ($stmt->fetch()) {
          $email_prefill = $email_token;
      } else {
          $error = "Lien d'invitation invalide ou expiré.";
      }
      $stmt->close();
    }

    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $email = trim($_POST['email']);
      $password = $_POST['password'];

      $stmt = $mysqli->prepare("SELECT id, nom, mot_de_passe, token_invitation FROM utilisateurs WHERE email = ? AND actif = 1");
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $stmt->store_result();

      if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $nom, $hash, $token_db);
        $stmt->fetch();
        
        if (password_verify($password, $hash)) {
          $_SESSION['admin_id'] = $id;
          $_SESSION['admin_nom'] = $nom;
          // Supprimer le token après première connexion
          if (!empty($token_db)) {
            $stmt2 = $mysqli->prepare("UPDATE utilisateurs SET token_invitation = NULL WHERE id = ?");
            $stmt2->bind_param("i", $id);
            $stmt2->execute();
            $stmt2->close();
          }

          header("Location: dashboard.php");
          exit;
        } else {
            $error = "Mot de passe incorrect.";
          }
      }
      else {
        $error = "Utilisateur non trouvé.";
      }
      $stmt->close();
    }
  ?>
  <!DOCTYPE html>
  <html lang="fr">
    <head>
      <?php include_once '../includes/meta-head.php'; ?>
      <link rel="stylesheet" href="../public/assets/css/ajouter_utilisateur.css">
      <link rel="stylesheet" href="../public/assets/css/header.css">
      <link rel="stylesheet" href="../public/assets/css/footer.css">
    </head>
    <body>
      <?php include_once '../includes/header.php'; ?>
      <main>
        <div class="form-box">
          <h2>Connexion Admin</h2>
          <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>
          <form method="POST" class="form-ajout-utilisateur">
            <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($email_prefill) ?>">
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
          </form>
        </div>
      </main>
      <?php include_once '../includes/footer.php'; ?>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
  </html>