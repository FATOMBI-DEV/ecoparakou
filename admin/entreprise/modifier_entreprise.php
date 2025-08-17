<?php
session_start();
include_once '../../includes/db.php';
include_once '../../includes/fonctions.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: ../login.php");
  exit;
}

$admin_id = $_SESSION['admin_id'];

// Vérification du rôle
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

// Récupération de l'entreprise
$id = intval($_GET['id'] ?? 0);
if (!$id) {
  header("Location: entreprises.php?error=id_invalide");
  exit;
}

$stmt = $mysqli->prepare("SELECT * FROM entreprises WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$entreprise = $res->fetch_assoc();
$stmt->close();

if (!$entreprise) {
  header("Location: liste_entreprise.php?error=notfound");
  exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nom           = trim($_POST['nom']);
  $slug          = trim($_POST['slug']);
  $email_contact = trim($_POST['email_contact']);
  $telephone     = trim($_POST['telephone']);
  $secteur_id    = intval($_POST['secteur_id']);
  $adresse       = trim($_POST['adresse']);
  $quartier      = trim($_POST['quartier']);
  $localisation  = trim($_POST['localisation']);
  $description   = trim($_POST['description']);
  //$statut        = trim($_POST['statut']);

  // Gestion du logo
  $logo = $entreprise['logo'];
  if (!empty($_FILES['logo']['name'])) {
    $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
    $filename = uniqid('logo_') . '.' . $ext;
    $upload_path = '../../public/uploads/' . $filename;

    if (move_uploaded_file($_FILES['logo']['tmp_name'], $upload_path)) {
      $logo = $filename;
    }
  }

  // Mise à jour SQL
  $stmt = $mysqli->prepare("UPDATE entreprises SET 
    nom = ?, slug = ?, email_contact = ?, telephone = ?, secteur_id = ?, 
    adresse = ?, quartier = ?, localisation = ?, description = ?, logo = ?
    WHERE id = ?");
  $stmt->bind_param("ssssissssss", 
    $nom, $slug, $email_contact, $telephone, $secteur_id, 
    $adresse, $quartier, $localisation, $description, $logo, $id);
  $stmt->execute();
  $stmt->close();

  
  // Log + notification
  log_action($admin_id, "Modification entreprise", "entreprises", $id);

$email_contact = $entreprise['email_contact'];
$sujet = "Modification de votre fiche entreprise";
$contenu = "
  <h3>Bonjour {$entreprise['nom']},</h3>
  <p>Votre fiche entreprise a été modifiée avec succès dans l’espace admin EcoParakou.</p>
  <p><strong>Date :</strong> " . date('d/m/Y H:i') . "</p>
  <p>Si vous n’êtes pas à l’origine de cette modification, veuillez nous contacter immédiatement.</p>
  <br>
  <p>— L’équipe EcoParakou</p>
";

envoyer_email($email_contact, $sujet, $contenu);

  header("Location: liste_entreprise.php?success=modifiee");
  exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier une entreprise</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .container {
      max-width: 960px;
      margin-top: 40px;
    }
    .logo-preview {
      max-height: 80px;
      border-radius: 4px;
      margin-top: 5px;
      box-shadow: 0 0 4px rgba(0,0,0,0.2);
    }
    .form-section {
      background-color: #ffffff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
  </style>
</head>
<body>

<div class="container">
  <h3 class="mb-4 text-primary"><i class="bi bi-pencil-square me-2"></i>Modifier l’entreprise</h3>

  <div class="form-section">
    <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate id="form-modif">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Nom</label>
          <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($entreprise['nom']) ?>" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Slug</label>
          <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($entreprise['slug']) ?>" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Email contact</label>
          <input type="email" name="email_contact" class="form-control" value="<?= htmlspecialchars($entreprise['email_contact']) ?>" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Téléphone</label>
          <input type="text" name="telephone" class="form-control" value="<?= htmlspecialchars($entreprise['telephone']) ?>" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Secteur</label>
          <select name="secteur_id" class="form-select" required>
            <?php
            $secteurs = $mysqli->query("SELECT id, nom FROM secteurs ORDER BY nom");
            while ($s = $secteurs->fetch_assoc()) {
              $selected = ($s['id'] == $entreprise['secteur_id']) ? 'selected' : '';
              echo "<option value='{$s['id']}' $selected>{$s['nom']}</option>";
            }
            ?>
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Adresse</label>
          <input type="text" name="adresse" class="form-control" value="<?= htmlspecialchars($entreprise['adresse']) ?>" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Quartier</label>
          <input type="text" name="quartier" class="form-control" value="<?= htmlspecialchars($entreprise['quartier']) ?>" required>
        </div>
       <div class="col-md-6 mb-3">
            <label for="localisation" class="form-label">Localisation (lien Google Maps)</label>
            <input
                type="url"
                name="localisation"
                id="localisation"
                class="form-control"
                value="<?= htmlspecialchars($entreprise['localisation']) ?>"
                placeholder="https://maps.google.com/..."
            >
            <div class="invalid-feedback">
                Veuillez entrer une URL valide (ex: https://maps.google.com/...).
            </div>
        </div>
        <div class="col-md-12 mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($entreprise['description']) ?></textarea>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Logo</label>
          <input type="file" name="logo" class="form-control">
          <?php if ($entreprise['logo']) : ?>
            <img src="../../uploads/logos/<?= $entreprise['logo'] ?>" alt="Logo actuel" class="logo-preview">
          <?php endif; ?>
        </div>
        
      </div>

      <div class="d-flex justify-content-between mt-4">
        <a href="entreprises.php" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left-circle me-1"></i>Retour
        </a>
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-circle me-1"></i>Enregistrer
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(() => {
  'use strict';
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
})();
</script>

</body>
</html>