
  <?php
    $page_title = "Accueil";
    include_once '../includes/constants.php';
    include_once '../includes/db.php';

    // Récupérations
    //$secteurs = $mysqli->query("SELECT nom, slug, description FROM secteurs ORDER BY ordre LIMIT 4");
    // Top 6 secteurs avec le plus d'entreprises
    $secteurs_populaires = $mysqli->query("SELECT s.nom, s.slug, s.description, COUNT(e.id) AS total
      FROM secteurs s
      LEFT JOIN entreprises e ON e.secteur_id = s.id AND e.statut = 'valide'
      GROUP BY s.id
      ORDER BY total DESC
      LIMIT 6
    ");
    $entreprises = $mysqli->query("SELECT nom, slug, logo, description FROM entreprises WHERE statut = 'valide' ORDER BY date_validation DESC LIMIT 6");
    $total_entreprises = $mysqli->query("SELECT COUNT(*) AS total FROM entreprises WHERE statut = 'valide'")->fetch_assoc()['total'];
    $total_secteurs = $mysqli->query("SELECT COUNT(*) AS total FROM secteurs")->fetch_assoc()['total'];
  ?>
  <!DOCTYPE html>
  <html lang="fr">

    <head>
      <?php include_once '../includes/meta-head.php'; ?>
      <link rel="stylesheet" href="assets/css/index.css">
      <link rel="stylesheet" href="assets/css/header.css">
    </head>

    <body>

      <?php include_once '../includes/header.php'; ?>

      <div class="container-fluid p-0">

        <!-- Section Hero -->
        <section class="hero-section d-flex align-items-center justify-content-center text-center">
          <div class="hero-overlay"></div>
          <div class="hero-content position-relative z-1">
            <h1 class="display-4 fw-bold"><?= SITE_NAME ?></h1>
            <p class="lead"><?= SITE_DESCRIPTION ?></p>
            <a href="inscription_entreprise.php" class="btn btn-accent mt-3">Inscrire mon entreprise</a>
          </div>
        </section>

        <!-- Secteurs populaires -->
        <section class="secteurs-section py-5 bg-light">
          <div class="container">
            <h3 class="mb-4 text-primary text-center">Secteurs les plus actifs</h3>
            <div class="row g-4">
              <?php while ($s = $secteurs_populaires->fetch_assoc()): ?>
                <div class="col-6 col-md-4 col-lg-2 secteur-fade">
                  <a href="secteur.php?slug=<?= $s['slug'] ?>" class="card secteur-card h-100 text-decoration-none">
                    <div class="card-body text-center">
                      <div class="stat-icon mb-2">
                        <i class="bi bi-diagram-3 fs-2"></i>
                      </div>
                      <h6 class="card-title mb-1"><?= htmlspecialchars($s['nom']) ?></h6>
                      <p class="card-text small text-muted"><?= substr(strip_tags($s['description']), 0, 60) ?>...</p>
                      <span class="badge bg-accent mt-2"><?= $s['total'] ?> entreprises</span>
                    </div>
                  </a>
                </div>
              <?php endwhile; ?>
            </div>
          </div>
        </section>

        <div class="container mt-5">

          <!-- Entreprises mises en avant -->
          <section class="entreprises-section mb-5">
            <h3 class="mb-4 text-primary text-center">Entreprises récemment validées</h3>
            <div class="row g-3">
              <?php while ($e = $entreprises->fetch_assoc()): ?>
                <div class="col-6 col-md-4 col-lg-2 entreprise-fade">
                  <a href="entreprise.php?slug=<?= $e['slug'] ?>" class="card entreprise-card h-100 text-decoration-none">
                    <?php
                      $logo = !empty($e['logo']) ? "/ecoparakou/public/uploads/{$e['logo']}" : "/ecoparakou/public/assets/img/logopardefaut.png";
                    ?>
                    <img src="<?= $logo ?>" class="card-img-top" alt="<?= htmlspecialchars($e['nom']) ?>">
                    <div class="card-body">
                      <h6 class="card-title mb-1"><?= htmlspecialchars($e['nom']) ?></h6>
                      <p class="card-text small text-muted"><?= substr(strip_tags($e['description']), 0, 60) ?>...</p>
                    </div>
                  </a>
                </div>
              <?php endwhile; ?>
            </div>
          </section>

          <!-- Statistiques -->
          <section class="stats-section text-center mb-5">
            <h3 class="mb-4 text-primary">Quelques chiffres</h3>
            <div class="row justify-content-center g-4">
              <div class="col-6 col-md-3">
                <div class="stat-card p-4 rounded shadow-sm">
                  <div class="stat-icon mb-2">
                    <i class="bi bi-building fs-2 text-accent"></i>
                  </div>
                  <h4 class="stat-value"><?= $total_entreprises ?></h4>
                  <p class="stat-label">Entreprises validées</p>
                </div>
              </div>
              <div class="col-6 col-md-3">
                <div class="stat-card p-4 rounded shadow-sm">
                  <div class="stat-icon mb-2">
                    <i class="bi bi-diagram-3 fs-2 text-accent"></i>
                  </div>
                  <h4 class="stat-value"><?= $total_secteurs ?></h4>
                  <p class="stat-label">Secteurs d’activité</p>
                </div>
              </div>
            </div>
          </section>

        </div>
      </div>

      <?php include_once '../includes/footer.php'; ?>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
  </html>