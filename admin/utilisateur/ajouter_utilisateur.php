  <?php
  session_start();
  include_once '../../includes/db.php';
  include_once '../../includes/fonctions.php';

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
          $error = "❌ Cet email est déjà enregistré.";
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
              $lien = "https://eco-parakou.bj/admin/login.php?token=" . $token;
              $sujet = "🎉 Accès à Eco Parakou";
              $message = "
  Bonjour $nom,

  Votre compte a été créé avec succès.

  🔗 Lien de connexion : $lien

  📧 Email : $email  
  🔑 Mot de passe initial : $mot_de_passe_clair

  Merci de modifier votre mot de passe après connexion.

  L'équipe Eco Parakou
              ";

              mail($email, $sujet, $message, "From: admin@eco-parakou.bj");

              // Redirection avec message
              header("Location: liste_utilisateur.php?success=" . urlencode("✅ Utilisateur ajouté et email envoyé à $email."));
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
    <meta charset="UTF-8">
    <title>Ajouter un utilisateur</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
      body {
        font-family: 'Poppins', sans-serif;
        background: #F5F1EB;
        padding: 40px;
      }

      .form-box {
        max-width: 500px;
        margin: auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      }

      h2 {
        text-align: center;
        color: #1F2A44;
        margin-bottom: 20px;
      }

      input, select {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
      }

      button {
        width: 100%;
        padding: 12px;
        background: #FF9800;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
      }

      .message {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
        font-weight: 600;
      }

      .error { background:#FFEBEE; color:#C62828; }
      .success { background:#E8F5E9; color:#2E7D32; }
    </style>
  </head>
  <body>
    <div class="form-box">
      <h2>Ajouter un utilisateur</h2>
      <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <form method="POST">
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
  </body>
  </html>