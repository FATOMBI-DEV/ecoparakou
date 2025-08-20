  <?php
    session_start();
    include_once '../../includes/db.php';
    include_once '../../includes/fonctions.php';
    $page_title = "Ajouter un utilisateur";

    $success = '';
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $nom = trim($_POST['nom']);
      $email = trim($_POST['email']);
      $role = $_POST['role'];
      // Vérification unicité email
      $stmt = $mysqli->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ?");
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $stmt->bind_result($count);
      $stmt->fetch();
      $stmt->close();

      if ($count > 0) {
        $error = " Cet email est déjà enregistré.";

      } else {
      
        // Mot de passe initial
        $mot_de_passe_clair = generatePassword();
        $mot_de_passe_hash = password_hash($mot_de_passe_clair, PASSWORD_DEFAULT);
        // Token d’invitation
        $token = bin2hex(random_bytes(16));
        // Insertion
        $stmt = $mysqli->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role, token_invitation, actif) VALUES (?, ?, ?, ?, ?, 1)");
        $stmt->bind_param("sssss", $nom, $email, $mot_de_passe_hash, $role, $token);
        if ($stmt->execute()) {
          // Envoi email
          $lien = BASE_URL . "admin/login.php?token=" . $token;
          
          $sujet = "Accès à Eco Parakou";
          $message = "Bonjour $nom, Votre compte a été créé avec succès.
          Lien de connexion : $lien
          Email : $email  
          Mot de passe initial : $mot_de_passe_clair
          Merci de modifier votre mot de passe après connexion.
          Equipe Eco Parakou";
           envoyer_notification($email, $sujet, $message);
          // Redirection avec message
          header("Location: liste_utilisateur.php?success=" . urlencode("Utilisateur ajouté et email envoyé à $email."));
          exit;

        } else {
          $error = "Erreur lors de l'ajout : " . $stmt->error;
        }
        $stmt->close();
      }
    }

    // Générateur de mot de passe
    function generatePassword($length = 10) {
      return substr(str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789@#'), 0, $length);
    }
  ?>
  <!DOCTYPE html>
  <html lang="fr">
    <head>
      <?php include_once '../../includes/meta-head.php'; ?>
      <link rel="stylesheet" href="../../public/assets/css/header.css">
      <link rel="stylesheet" href="../../public/assets/css/ajouter_utilisateur.css">
      <link rel="stylesheet" href="../../public/assets/css/footer.css">
    </head>
    <body>
      <?php include_once '../../includes/header.php'; ?>
      
      <main>
        <div class="form-box">
          <h2>Ajouter un utilisateur</h2>
          <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>
          
          <form method="POST" class="form-ajout-utilisateur">
            <input type="text" name="nom" placeholder="Nom complet" required>
            <input type="email" name="email" placeholder="Email" required>
            <select name="role" required>
              <option value="">-- Sélectionner un rôle --</option>
              <option value="admin">Administrateur</option>
              <option value="moderateur">Modérateur</option>
            </select>
            <button type="submit">Créer et envoyer le lien</button>
          </form>
        </div>
      </main>

      <?php include_once '../../includes/footer.php'; ?>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
  </html>