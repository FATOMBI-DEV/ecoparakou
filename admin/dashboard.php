  <?php
    session_start();
    $page_title = "Dashboard Admin";
    include_once '../includes/db.php';
    include_once '../includes/fonctions.php';

    if (!isset($_SESSION['admin_id'])) {
      header("Location: login.php");
      exit;
    }

    $admin_id = $_SESSION['admin_id'];

    // Récupération du rôle
    $stmt = $mysqli->prepare("SELECT nom, role FROM utilisateurs WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $stmt->bind_result($nom_admin, $role);
    $stmt->fetch();
    $stmt->close();

    // Statistiques
    $nb_entreprises = $mysqli->query("SELECT COUNT(*) FROM entreprises")->fetch_row()[0];
    $nb_en_attente = $mysqli->query("SELECT COUNT(*) FROM entreprises WHERE statut = 'en_attente'")->fetch_row()[0];
    $nb_secteurs = $mysqli->query("SELECT COUNT(*) FROM secteurs")->fetch_row()[0];
    $nb_utilisateurs = $mysqli->query("SELECT COUNT(*) FROM utilisateurs")->fetch_row()[0];
    $nb_logs = $mysqli->query("SELECT COUNT(*) FROM logs_actions")->fetch_row()[0];
    $messages = $mysqli->query("SELECT id, nom, email, sujet, date_envoi
      FROM messages_contact
      ORDER BY date_envoi DESC
      LIMIT 5
    ");
  ?>

  <!DOCTYPE html>
  <html lang="fr">
    <head>
      <?php include_once '../includes/meta-head.php'; ?>
      <link rel="stylesheet" href="../public/assets/css/dashboard.css">
      <link rel="stylesheet" href="../public/assets/css/header.css">
    </head>

    <body>

      <?php include_once '../includes/header.php'; ?>

      <!-- Bouton menu mobile -->
      <button id="menuToggle"><i class="bi bi-list"></i></button>

      <!-- Sidebar -->
      <div class="sidebar" id="sidebar">
        <h4>EcoParakou</h4>
        <a href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
        <a href="entreprise/liste_entreprise.php"><i class="bi bi-building me-2"></i>Entreprises</a>
        <a href="secteurs/liste_secteur.php"><i class="bi bi-diagram-3 me-2"></i>Secteurs</a>
        <?php if ($role === 'admin'): ?>
          <a href="utilisateur/liste_utilisateur.php"><i class="bi bi-people me-2"></i>Utilisateurs</a>
          <a href="logs/liste_logs.php"><i class="bi bi-journal-text me-2"></i>Logs</a>
        <?php endif; ?>
        <a href="messages/messages_contact.php"><i class="bi bi-envelope-fill me-1"></i> Messages</a>
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
      </div>

      <!-- Main content -->
      <main class="main-content">
        <div class="dashboard-header">
          <h2>
            <i class="bi bi-person-circle me-2"></i> Bienvenue, <?= htmlspecialchars($nom_admin) ?>
          </h2>
          <small><i class="bi bi-shield-lock me-1"></i> Rôle : <?= $role ?></small>
        </div>

        <div class="row g-4">
          <div class="col-md-3">
            <div class="card p-3 shadow-sm">
              <h5 class="card-title"><i class="bi bi-building me-1"></i> Entreprises</h5>
              <p class="fs-3 text-accent"><?= $nb_entreprises ?></p>
              <a href="entreprise/liste_entreprise.php" class="btn btn-accent w-100">
                <i class="bi bi-gear-fill me-1"></i> Gérer
              </a>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card p-3 shadow-sm">
              <h5 class="card-title"><i class="bi bi-hourglass-split me-1"></i> En attente</h5>
              <p class="fs-3 text-danger"><?= $nb_en_attente ?></p>
              <a href="entreprise/entreprise_en_attente.php" class="btn btn-outline-danger w-100">
                <i class="bi bi-check2-circle me-1"></i> Valider
              </a>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card p-3 shadow-sm">
              <h5 class="card-title"><i class="bi bi-diagram-3 me-1"></i> Secteurs</h5>
              <p class="fs-3 text-primary"><?= $nb_secteurs ?></p>
              <a href="secteurs/liste_secteur.php" class="btn btn-outline-primary w-100">
                <i class="bi bi-eye-fill me-1"></i> Voir
              </a>
            </div>
          </div>

          <?php if ($role === 'admin'): ?>
            <div class="col-md-3">
              <div class="card p-3 shadow-sm">
                <h5 class="card-title"><i class="bi bi-people-fill me-1"></i> Utilisateurs</h5>
                <p class="fs-3 text-muted"><?= $nb_utilisateurs ?></p>
                <a href="utilisateur/liste_utilisateur.php" class="btn btn-outline-dark w-100">
                  <i class="bi bi-person-lines-fill me-1"></i> Gérer
                </a>
              </div>
            </div>

            <div class="col-md-3">
              <div class="card p-3 shadow-sm">
                <h5 class="card-title"><i class="bi bi-journal-text me-1"></i> Logs</h5>
                <p class="fs-3 text-muted"><?= $nb_logs ?></p>
                <a href="logs/liste_logs.php" class="btn btn-outline-secondary w-100">
                  <i class="bi bi-clock-history me-1"></i> Historique
                </a>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <div class="mt-5">
          <h4><i class="bi bi-lightning-fill me-2 text-warning"></i> Actions rapides</h4>
          <div class="d-flex flex-wrap gap-3 mt-3">
            <a href="../public/inscrit_entreprise.php" class="btn btn-accent">
              <i class="bi bi-plus-circle me-1"></i> Ajouter une entreprise
            </a>
            <a href="secteurs/ajouter_secteur.php" class="btn btn-outline-primary">
              <i class="bi bi-plus-circle me-1"></i> Ajouter un secteur
            </a>
            <?php if ($role === 'admin'): ?>
            <a href="utilisateur/ajouter_utilisateur.php" class="btn btn-outline-dark">
              <i class="bi bi-person-plus me-1"></i> Ajouter un utilisateur
            </a>
            <?php endif; ?>
          </div>
        </div>

        <div class="mt-5">
          <h4><i class="bi bi-envelope-paper-fill me-2 text-primary"></i> Derniers messages reçus</h4>
          <div class="table-responsive mt-3">
            <table class="table table-bordered table-hover bg-white">
              <thead class="table-light">
                <tr>
                  <th><i class="bi bi-person-fill me-1"></i> Nom</th>
                  <th><i class="bi bi-envelope-fill me-1"></i> Email</th>
                  <th><i class="bi bi-chat-left-text me-1"></i> Sujet</th>
                  <th><i class="bi bi-calendar-event me-1"></i> Date</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($msg = $messages->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($msg['nom']) ?></td>
                    <td><?= htmlspecialchars($msg['email']) ?></td>
                    <td><?= htmlspecialchars($msg['sujet']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($msg['date_envoi'])) ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </main>

      <?php include_once '../includes/footer.php'; ?>

      <!-- Scripts -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
      <script>
        // Animation au chargement
        document.addEventListener("DOMContentLoaded", () => {
          const cards = document.querySelectorAll('.card');
          cards.forEach((card, i) => {
            card.style.opacity = 0;
            card.style.transform = "translateY(20px)";
            setTimeout(() => {
              card.style.transition = "all 0.6s ease";
              card.style.opacity = 1;
              card.style.transform = "translateY(0)";
            }, 100 * i);
          });
        });


        const menuToggle = document.getElementById('menuToggle');

        menuToggle.addEventListener('click', () => {
          menuToggle.classList.toggle('active');
        });


        // Toggle sidebar mobile
        document.getElementById("menuToggle").addEventListener("click", () => {
          
          document.getElementById("sidebar").classList.toggle("active");
        });
      </script>
    </body>
  </html>