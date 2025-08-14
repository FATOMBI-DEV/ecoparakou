<?php
$page_title = "Résultats de recherche";
include_once '../includes/meta-head.php';
include_once '../includes/header.php';
include_once '../includes/db.php';
include_once '../includes/fonctions.php';

$q = trim($_GET['q'] ?? '');
?>

<div class="container mt-5">
  <h2 class="mb-4">Résultats pour : <strong><?= htmlspecialchars($q) ?></strong></h2>

  <div class="row">
    <?php
    if ($q !== '') {
      $stmt = $mysqli->prepare("SELECT nom, slug, description, logo FROM entreprises WHERE statut = 'valide' AND nom LIKE CONCAT('%', ?, '%')");
      $stmt->bind_param("s", $q);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        while ($e = $result->fetch_assoc()) {
          ?>
          <div class="col-md-4 mb-4">
            <div class="card h-100">
              <?php if (!empty($e['logo'])): ?>
                <img src="/uploads/<?= $e['logo'] ?>" class="card-img-top" alt="<?= htmlspecialchars($e['nom']) ?>">
              <?php endif; ?>
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($e['nom']) ?></h5>
                <p class="card-text"><?= substr(strip_tags($e['description']), 0, 100) ?>...</p>
                <a href="/entreprise.php?slug=<?= $e['slug'] ?>" class="btn btn-primary">Voir la fiche</a>
              </div>
            </div>
          </div>
          <?php
        }
      } else {
        echo '<p>Aucune entreprise trouvée.</p>';
      }
    } else {
      echo '<p>Veuillez entrer un mot-clé pour rechercher.</p>';
    }
    ?>
  </div>
</div>

<?php include_once '../includes/footer.php'; ?>