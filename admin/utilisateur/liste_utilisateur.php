<?php
session_start();
include_once '../../includes/db.php';
include_once '../../includes/fonctions.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: ../login.php");
  exit;
}

$admin_id = $_SESSION['admin_id'];
$stmt = $mysqli->prepare("SELECT role FROM utilisateurs WHERE id = ? AND actif = 1");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

if ($role !== 'admin') {
  header("Location: ../access-denied.php");
  exit;
}

// 📋 Récupération enrichie des utilisateurs
$users = $mysqli->query("
  SELECT id, nom, email, role, actif, date_creation, token_invitation
  FROM utilisateurs
  ORDER BY date_creation DESC
");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des utilisateurs</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #F5F1EB;
      color: #333333;
    }
    .table th {
      background-color: #1F2A44;
      color: #ffffff;
    }
    .btn-accent {
      background-color: #FF9800;
      color: #ffffff;
    }
    .btn-accent:hover {
      background-color: #e68900;
    }
    .token {
      font-size: 0.85em;
      color: #6c757d;
      word-break: break-word;
    }
  </style>
  <script>
    /*function confirmerSuppression(id, nom) {
      if (confirm(`🗑️ Supprimer l'utilisateur "${nom}" ? Cette action est irréversible.`)) {
        window.location.href = `supprimer.php?id=${id}`;
      }
    }*/
  </script>
</head>

<body>
  <div class="container py-5">
    <h2 class="mb-4">👥 Gestion des utilisateurs</h2>
    <a href="ajouter_utilisateur.php" class="btn btn-accent mb-3">
      <i class="bi bi-person-plus"></i> Ajouter un utilisateur
    </a>

    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success text-center fw-semibold">
        <?= htmlspecialchars($_GET['success']) ?>
      </div>
    <?php endif; ?>

    <table class="table table-bordered table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Rôle</th>
          <th>Statut</th>
          <th>Créé le</th>
          <th>Token d'invitation</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($u = $users->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($u['nom']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['role'] === 'admin' ? '<i class="bi bi-shield-lock"></i> Admin' : '<i class="bi bi-person-gear"></i> Modérateur' ?></td>
            <td>
              <span class="badge <?= $u['actif'] ? 'bg-success' : 'bg-secondary' ?>">
                <?= $u['actif'] ? 'Actif' : 'Inactif' ?>
              </span>
            </td>
            <td><?= date('d/m/Y', strtotime($u['date_creation'])) ?></td>
            <td>
              <?php if (!empty($u['token_invitation'])): ?>
                <code><?= htmlspecialchars($u['token_invitation']) ?></code>
              <?php else: ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="modifier_utilisateur.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-primary" title="Modifier">
                <i class="bi bi-pencil-square"></i>
              </a>

              <?php if ($u['actif']): ?>
                <a href="toggle_activation.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-warning" title="Désactiver" onclick="return confirm('Désactiver ce compte ?')">
                  <i class="bi bi-person-dash"></i>
                </a>
              <?php else: ?>
                <a href="toggle_activation.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-success" title="Activer" onclick="return confirm('Réactiver ce compte ?')">
                  <i class="bi bi-person-check"></i>
                </a>
              <?php endif; ?>

              <button onclick="confirmerSuppression(<?= $u['id'] ?>, '<?= addslashes($u['nom']) ?>')" class="btn btn-sm btn-outline-danger" title="Supprimer">
                <i class="bi bi-trash"></i>
              </button>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <script>
    function confirmerSuppression(id, nom) {
      const confirmation = confirm(`🗑️ Voulez-vous vraiment supprimer l'utilisateur "${nom}" ? Cette action est irréversible.`);
      if (confirmation) {
        window.location.href = `supprimer_utilisateur.php?id=${id}`;
      }
    }

    // Activation des tooltips Bootstrap
    const tooltipTriggerList = document.querySelectorAll('[title]');
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>