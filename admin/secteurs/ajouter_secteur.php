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

$erreur = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nom = trim($_POST['nom']);

  if (!empty($_POST['ordre'])) {
    $ordre = intval($_POST['ordre']);
  } else {
    $res = $mysqli->query("SELECT MAX(ordre) FROM secteurs");
    $max = $res->fetch_row()[0] ?? 0;
    $ordre = $max + 1;
  }

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
      $stmt = $mysqli->prepare("INSERT INTO secteurs (nom, slug, ordre) VALUES (?, ?, ?)");
      $stmt->bind_param("ssi", $nom, $slug, $ordre);

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
  <meta charset="UTF-8">
  <title>Ajouter un secteur</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #F5F1EB; font-family: 'Poppins', sans-serif; }
    .form-control:focus { border-color: #FF9800; box-shadow: 0 0 0 0.2rem rgba(255,152,0,.25); }
    .btn-accent { background-color: #FF9800; color: #fff; }
    .btn-accent:hover { background-color: #e68900; }
    .icon-title { font-size: 1.5rem; color: #FF9800; margin-right: 0.5rem; }
  </style>
</head>
<body>
  <div class="container py-5">
    <h2 class="mb-4">
      <i class="bi bi-plus-circle icon-title"></i> Ajouter un secteur
    </h2>

    <?php if ($erreur): ?>
      <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= $erreur ?></div>
    <?php elseif ($success): ?>
      <div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="nom" class="form-label">Nom du secteur</label>
        <input type="text" name="nom" id="nom" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="ordre" class="form-label">Ordre (optionnel)</label>
        <input type="number" name="ordre" id="ordre" class="form-control" placeholder="Laisser vide pour ordre automatique">
      </div>
      <button type="submit" class="btn btn-accent">
        <i class="bi bi-plus-circle me-1"></i> Ajouter
      </button>
      <a href="liste.php" class="btn btn-outline-secondary ms-2">
        <i class="bi bi-arrow-left me-1"></i> Retour
      </a>
    </form>
  </div>
</body>
</html>