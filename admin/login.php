<?php
session_start();
include_once '../includes/db.php';
include_once '../includes/fonctions.php';

$error = '';
$email_prefill = '';

// 🔐 Si token présent dans l'URL
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

// 🔄 Traitement du formulaire
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

            // 🧼 Supprimer le token après première connexion
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
    } else {
        $error = "Utilisateur non trouvé.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --color-primary: #1F2A44;
      --color-accent: #FF9800;
      --color-bg: #F5F1EB;
      --color-text: #333333;
      --color-white: #ffffff;
      --color-muted: #6c757d;
      --color-hover: #f0f0f0;
      --font-main: 'Poppins', sans-serif;
    }

    body {
      margin: 0;
      font-family: var(--font-main);
      background: var(--color-bg);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .login-box {
      background: var(--color-white);
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
      animation: fadeIn 0.8s ease-out;
    }

    h2 {
      margin-bottom: 20px;
      color: var(--color-primary);
      text-align: center;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid var(--color-muted);
      border-radius: 8px;
      font-size: 16px;
    }

    button {
      width: 100%;
      padding: 12px;
      background: var(--color-accent);
      color: var(--color-white);
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background: #e68900;
    }

    .error {
      color: red;
      text-align: center;
      margin-bottom: 10px;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Connexion Admin</h2>
    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
      <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($email_prefill) ?>">
      <input type="password" name="password" placeholder="Mot de passe" required>
      <button type="submit">Se connecter</button>
    </form>
  </div>
</body>
</html>