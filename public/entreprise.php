  <?php
    $page_title = "Fiche d'entreprise";
    include_once '../includes/db.php';

    $slug = trim($_GET['slug'] ?? '');

    if ($slug === '') {
      include_once '../includes/header.php';
      echo "<main class='container mt-5'><p class='text-danger'>Entreprise introuvable.</p></main>";
      include_once '../includes/footer.php';
      exit;
    }

    $stmt = $mysqli->prepare("
      SELECT e.*, s.nom AS secteur_nom 
      FROM entreprises e 
      LEFT JOIN secteurs s ON e.secteur_id = s.id 
      WHERE e.slug = ? AND e.statut = 'valide'
    ");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
      include_once '../includes/header.php';
      echo "<main class='container mt-5'><p class='text-danger'>Entreprise non trouvée ou non validée.</p></main>";
      include_once '../includes/footer.php';
      exit;
    }

    $e = $result->fetch_assoc();
    $logo = !empty($e['logo']) ? "/ecoparakou/public/uploads/{$e['logo']}" : "/ecoparakou/public/assets/img/logopardefaut.png";
  ?>

  <!DOCTYPE html>
  <html lang="fr">

    <head>
      <?php include_once '../includes/meta-head.php'; ?>
      <link rel="stylesheet" href="assets/css/header.css">
      <link rel="stylesheet" href="assets/css/footer.css">
      <link rel="stylesheet" href="assets/css/entreprises.css">
    </head>

    <body>

      <?php include_once '../includes/header.php'; ?>

      <main class="container mt-5">
        <div class="fiche-wrapper">
          <!-- Carte branding -->
          <div class="fiche-card text-center">
            <img src="<?= $logo ?>" alt="<?= htmlspecialchars($e['nom']) ?>" class="entreprise-logo">
            <div class="entreprise-nom"><?= htmlspecialchars($e['nom']) ?></div>
            <div class="entreprise-secteur"><i class="bi bi-diagram-3-fill me-1"></i><?= htmlspecialchars($e['secteur_nom']) ?></div>
            <div class="info-item"><i class="bi bi-signpost-fill"></i><?= htmlspecialchars($e['quartier']) ?></div>
            <div class="info-item"><i class="bi bi-house-door-fill"></i><?= htmlspecialchars($e['adresse']) ?></div>
            <div class="info-item"><i class="bi bi-telephone-fill"></i><?= htmlspecialchars($e['telephone']) ?></div>
            <div class="info-item"><i class="bi bi-envelope-fill"></i><?= htmlspecialchars($e['email_contact']) ?></div>
            <div class="info-item d-flex flex-wrap align-items-center gap-1">
  <i class="bi bi-geo-alt-fill text-accent"></i>
  <span class="text-muted">Parakou, <?= htmlspecialchars($e['quartier']) ?> —</span>
  <a href="<?= htmlspecialchars($e['localisation']) ?>" target="_blank" rel="noopener noreferrer" class="text-primary text-decoration-underline">Google Maps</a>
</div>
          </div>

          <!-- Carte contenu -->
          <div class="fiche-card">
            <h3>Présentation</h3>
            <p class="entreprise-description"><?= nl2br(htmlspecialchars($e['description'])) ?></p>
          </div>
        </div>
      </main>

      <?php include_once '../includes/footer.php'; ?>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
      
    </body>
  </html>