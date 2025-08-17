<?php
session_start();
include_once '../../includes/db.php';
include_once '../../includes/fonctions.php';

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
    $error = "❌ Cet email est déjà utilisé.";
  } else {
    $stmt = $mysqli->prepare("UPDATE utilisateurs SET nom = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nom, $email, $role, $id);
    if ($stmt->execute()) {
      log_action($admin_id, "Modification de l'utilisateur", "utilisateurs", $id);
      header("Location: utilisateurs.php?success=" . urlencode("✅ Utilisateur modifié."));
      exit;
    } else {
      $error = "❌ Erreur lors de la modification : " . $stmt->error;
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
  <meta charset="UTF-8">
  <title>Modifier utilisateur</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; background: #F5F1EB; padding: 40px; }
    .form-box { max-width: 500px; margin: auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    h2 { text-align: center; color: #1F2A44; margin-bottom: 20px; }
    input, select { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 8px; font-size: 16px; }
    button { width: 100%; padding: 12px; background: #2196F3; color: #fff; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; }
    .message { padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: 600; }
    .error { background:#FFEBEE; color:#C62828; }
  </style>
</head>
<body>
  <div class="form-box">
    <h2>Modifier utilisateur</h2>
    <?php if ($error): ?>
      <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
      <input type="text" name="nom" value="<?= htmlspecialchars($nom) ?>" required>
      <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
      <select name="role" required>
        <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Administrateur</option>
        <option value="moderateur" <?= $role === 'moderateur' ? 'selected' : '' ?>>Modérateur</option>
      </select>
      <button type="submit">Enregistrer les modifications</button>
    </form>
  </div>
</body>
</html>