  <?php
    require_once '../../includes/db.php';
    require_once '../../includes/fonctions.php';
    $page_title = "Logs d’actions";
    session_start();

    // Vérification du rôle via la base
    if (!isset($_SESSION['admin_id'])) {
      header("Location: ../login.php");
      exit;
    }

    $admin_id = $_SESSION['admin_id'];
    $stmt = $mysqli->prepare("SELECT role FROM utilisateurs WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if (!$admin || !in_array($admin['role'], ['admin', 'moderateur'])) {
      die("Accès refusé.");
    }

    // Récupération des filtres
    $filtre_utilisateur = $_GET['utilisateur'] ?? '';
    $filtre_table = $_GET['table'] ?? '';
    $filtre_date_debut = $_GET['date_debut'] ?? '';
    $filtre_date_fin = $_GET['date_fin'] ?? '';

    // Validation des dates
    function est_date_valide($date) {
      return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date);
    }

    // Construction dynamique de la requête
    $conditions = [];
    $params = [];
    $types = '';

    if ($filtre_utilisateur !== '') {
      $conditions[] = 'l.utilisateur_id = ?';
      $params[] = $filtre_utilisateur;
      $types .= 'i';
    }
    if ($filtre_table !== '') {
      $conditions[] = 'l.table_cible = ?';
      $params[] = $filtre_table;
      $types .= 's';
    }
    if ($filtre_date_debut !== '' && est_date_valide($filtre_date_debut)) {
      $conditions[] = 'l.date_action >= ?';
      $params[] = $filtre_date_debut . ' 00:00:00';
      $types .= 's';
    }
    if ($filtre_date_fin !== '' && est_date_valide($filtre_date_fin)) {
      $conditions[] = 'l.date_action <= ?';
      $params[] = $filtre_date_fin . ' 23:59:59';
      $types .= 's';
    }

    $sql = "
      SELECT l.*, u.nom AS utilisateur
      FROM logs_actions l
      INNER JOIN utilisateurs u ON u.id = l.utilisateur_id
    ";
    if (!empty($conditions)) {
      $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }
    $sql .= ' ORDER BY l.date_action DESC LIMIT 100';

    $stmt = $mysqli->prepare($sql);
    if (!empty($params)) {
      $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
  ?>

  <!DOCTYPE html>
  <html lang="fr">
    <head>
      <?php include_once '../../includes/meta-head.php'; ?>
      <link rel="stylesheet" href="../../public/assets/css/liste_logs.css">
      <link rel="stylesheet" href="../../public/assets/css/header.css">
      <link rel="stylesheet" href="../../public/assets/css/footer.css">
    </head>
    <body>
      <?php include_once '../../includes/header.php'; ?>
      <main>
        <div class="container mt-4">
          <h3 class="mb-4 text-primary">
            <i class="bi bi-journal-text me-2"></i> Historique des actions
          </h3>

          <!-- Formulaire de filtres -->
          <form method="get" class="row g-3 mb-4">
            <div class="col-md-3">
              <label><i class="bi bi-person-fill me-1"></i> Utilisateur</label>
              <select name="utilisateur" class="form-select">
                <option value="">Tous</option>
                <?php
                $users = $mysqli->query("SELECT id, nom FROM utilisateurs ORDER BY nom");
                while ($u = $users->fetch_assoc()):
                ?>
                  <option value="<?= $u['id'] ?>" <?= $filtre_utilisateur == $u['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['nom']) ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="col-md-3">
              <label><i class="bi bi-table me-1"></i> Table cible</label>
              <input type="text" name="table" class="form-control" value="<?= htmlspecialchars($filtre_table) ?>">
            </div>
            <div class="col-md-3">
              <label><i class="bi bi-calendar-date me-1"></i> Date début</label>
              <input type="date" name="date_debut" class="form-control" value="<?= htmlspecialchars($filtre_date_debut) ?>">
            </div>
            <div class="col-md-3">
              <label><i class="bi bi-calendar-date me-1"></i> Date fin</label>
              <input type="date" name="date_fin" class="form-control" value="<?= htmlspecialchars($filtre_date_fin) ?>">
            </div>
            <div class="col-12 text-end">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel-fill me-1"></i> Filtrer
              </button>
              <a href="liste_logs.php" class="btn btn-secondary">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Réinitialiser
              </a>
            </div>
          </form>

          <!-- Tableau des logs -->
          <table class="table table-bordered table-hover bg-white">
            <thead class="table-light">
              <tr>
                <th><i class="bi bi-hash"></i></th>
                <th><i class="bi bi-person-fill"></i> Utilisateur</th>
                <th><i class="bi bi-pencil-fill"></i> Action</th>
                <th><i class="bi bi-table"></i> Table</th>
                <th><i class="bi bi-key-fill"></i> ID cible</th>
                <th><i class="bi bi-clock-fill"></i> Date</th>
                <th><i class="bi bi-eye-fill"></i> Détails</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($log = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= $log['id'] ?></td>
                  <td><?= htmlspecialchars($log['utilisateur'] ?? '') ?></td>
                  <td><?= htmlspecialchars($log['action']) ?></td>
                  <td><?= htmlspecialchars($log['table_cible']) ?></td>
                  <td><?= $log['cible_id'] ?></td>
                  <td><?= date('d/m/Y H:i', strtotime($log['date_action'])) ?></td>
                  <td>
                    <a href="details_logs.php?id=<?= $log['id'] ?>" class="btn btn-sm btn-outline-info">
                      <i class="bi bi-search me-1"></i> Voir
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </main>
      <?php include_once '../../includes/footer.php'; ?>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
  </html>