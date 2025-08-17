<?php
session_start();
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
$stmt = $mysqli->prepare("SELECT nom, slug, ordre FROM secteurs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($nom_actuel, $slug_actuel, $ordre_actuel);
$stmt->fetch();
$stmt->close();

$erreur = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nom = trim($_POST['nom']);
  $ordre = isset($_POST['ordre']) ? intval($_POST['ordre']) : null;

  if (empty($nom)) {
    $erreur = "Le nom du secteur est requis.";
  } else {
    $slug = generer_slug($nom);

    $stmt = $mysqli->prepare("UPDATE secteurs SET nom = ?, slug = ?, ordre = ? WHERE id = ?");
    $stmt->bind_param("ssii", $nom, $slug, $ordre, $id);
    $stmt->execute();
    $stmt->close();

    
    log_action($admin_id, "Modification du secteur : $nom", "secteurs", $id);
    
    $success = "Secteur modifié avec succès.";
    $nom_actuel = $nom;
    $slug_actuel = $slug;
    $ordre_actuel = $ordre;
  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier un secteur</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #F5F1EB; font-family: 'Poppins', sans-serif; }
    .form-control:focus { border-color: #FF9800; box-shadow: 0 0 0 0.2rem rgba(255,152,0,.25); }
    .btn-accent { background-color: #FF9800; color: #fff; }
    .btn-accent:hover { background-color: #e68900; }
  </style>
</head>
<body>
  <div class="container py-5">
    <h2 class="mb-4">✏️ Modifier le secteur</h2>

    <?php if ($erreur): ?>
      <div class="alert alert-danger"><?= $erreur ?></div>
    <?php elseif ($success): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="nom" class="form-label">Nom du secteur</label>
        <input type="text" name="nom" id="nom" class="form-control" value="<?= htmlspecialchars($nom_actuel) ?>" required>
      </div>
      <div class="mb-3">
        <label for="ordre" class="form-label">Ordre</label>
        <input type="number" name="ordre" id="ordre" class="form-control" value="<?= $ordre_actuel ?>">
      </div>
      <button type="submit" class="btn btn-accent">Enregistrer</button>
      <a href="liste.php" class="btn btn-outline-secondary ms-2">Retour</a>
    </form>
  </div>
</body>
</html>