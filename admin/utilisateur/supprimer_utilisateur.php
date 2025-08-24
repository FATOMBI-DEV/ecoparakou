  <?php
    session_start();
    include_once '../../includes/db.php';
    include_once '../../includes/fonctions.php';

    if (!isset($_SESSION['admin_id'])) {
      header("Location: ../login.php");
      exit;
    }

    $admin_id = $_SESSION['admin_id'];
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id <= 0) {
      echo "ID invalide.";
      exit;
    }

    // Vérification du rôle
    $stmt = $mysqli->prepare("SELECT role FROM utilisateurs WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $stmt->bind_result($role);
    $stmt->fetch();
    $stmt->close();

    if ($role !== 'admin') {
      echo "Accès refusé.";
      exit;
    }

    // Suppression
    $stmt = $mysqli->prepare("DELETE FROM utilisateurs WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
      log_action($admin_id, "Suppression de l'utilisateur", "utilisateurs", $id);
      header("Location: liste_utilisateur.php?success=" . urlencode("Utilisateur supprimé."));
    } else {
      echo "Erreur lors de la suppression.";
    }
    $stmt->close();
  ?>