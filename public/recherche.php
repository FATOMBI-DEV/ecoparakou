<?php
$page_title = "Résultats de recherche";
include_once '../includes/db.php';
$q = trim($_GET['q'] ?? '');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <?php include_once '../includes/meta-head.php'; ?>
  <link rel="stylesheet" href="assets/css/header.css">
  <link rel="stylesheet" href="assets/css/footer.css">
  <link rel="stylesheet" href="assets/css/recherche.css">
</head>
<body>

<?php include_once '../includes/header.php'; ?>

<main>
  <div class="container">
    <h2>Résultats pour : <strong><?= htmlspecialchars($q) ?></strong></h2>

    <div class="row">
      <?php
      if ($q !== '') {
        $stmt = $mysqli->prepare("SELECT nom, slug, description, logo, quartier, email_contact 
          FROM entreprises 
          WHERE statut = 'valide' AND (
            nom LIKE CONCAT('%', ?, '%') OR
            description LIKE CONCAT('%', ?, '%') OR
            quartier LIKE CONCAT('%', ?, '%') OR
            email_contact LIKE CONCAT('%', ?, '%')
          )
        ");
        $stmt->bind_param("ssss", $q, $q, $q, $q);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          while ($e = $result->fetch_assoc()) {
            $logo = !empty($e['logo']) ? "/ecoparakou/public/uploads/{$e['logo']}" : "/ecoparakou/public/assets/img/logopardefaut.png";
            ?>
            <a href="/ecoparakou/public/entreprise.php?slug=<?= $e['slug'] ?>" class="entreprise-card">
              <img src="<?= $logo ?>" alt="<?= htmlspecialchars($e['nom']) ?>">
              <div class="card-body">
                <h5><?= htmlspecialchars($e['nom']) ?></h5>
                <p><?= substr(strip_tags($e['description']), 0, 80) ?>...</p>
                <p><i class="bi bi-geo-alt-fill me-1"></i><?= htmlspecialchars($e['quartier']) ?></p>
              </div>
            </a>
            <?php
          }
        } else {
          echo '<p class="no-result">Aucune entreprise trouvée.</p>';
        }
      } else {
        echo '<p class="no-result">Veuillez entrer un mot-clé pour rechercher.</p>';
      }
      ?>
    </div>
  </div>
</main>

<?php include_once '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>