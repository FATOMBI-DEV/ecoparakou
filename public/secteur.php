<?php
$page_title = "Entreprises par secteur";
include_once '../includes/meta-head.php';
include_once '../includes/header.php';
include_once '../includes/db.php';

$slug = trim($_GET['slug'] ?? '');

if ($slug === '') {
  echo "<div class='container mt-5'><p>Secteur introuvable.</p></div>";
  include_once '../includes/footer.php';
  exit;
}

// Récupérer le secteur
$stmt = $mysqli->prepare("SELECT id, nom FROM secteurs WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo "<div class='container mt-5'><p>Secteur non trouvé.</p></div>";
  include_once '../includes/footer.php';
  exit;
}

$secteur = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <?php include_once '../includes/meta-head.php'; ?>
  <link rel="stylesheet" href="assets/css/index.css">
  <link rel="stylesheet" href="assets/css/secteurs.css">
  <link rel="stylesheet" href="assets/css/footer.css">
</head>
<body>

<main>
  <div class="container mt-5">
    <h2 class="mb-4 text-primary">Entreprises du secteur : <strong><?= htmlspecialchars($secteur['nom']) ?></strong></h2>

    <div class="row g-3">
      <?php
      $stmt = $mysqli->prepare("SELECT nom, slug, logo, quartier, telephone FROM entreprises WHERE secteur_id = ? AND statut = 'valide'");
      $stmt->bind_param("i", $secteur['id']);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        while ($e = $result->fetch_assoc()) {
          $logo = !empty($e['logo']) ? "/uploads/{$e['logo']}" : "/ecoparakou/public/assets/img/logopardefaut.png";
          ?>
          <div class="col-6 col-md-3 col-lg-2 col-xl-2">
            <a href="entreprise.php?slug=<?= $e['slug'] ?>" class="entreprise-card h-100">
              <div class="card-body">
                <img src="<?= $logo ?>" alt="<?= htmlspecialchars($e['nom']) ?>" class="entreprise-logo">
                <h5 class="card-title"><?= htmlspecialchars($e['nom']) ?></h5>
                <p class="card-text"><i class="bi bi-geo-alt-fill me-1"></i><?= htmlspecialchars($e['quartier']) ?></p>
                <p class="card-text"><i class="bi bi-telephone-fill me-1"></i><?= htmlspecialchars($e['telephone']) ?></p>
              </div>
            </a>
          </div>
          <?php
        }
      } else {
        echo '<p class="text-muted">Aucune entreprise trouvée dans ce secteur.</p>';
      }
      ?>
    </div>
  </div>
</main>
<?php include_once '../includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

