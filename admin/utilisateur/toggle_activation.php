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

    // Récupération de l'état actuel
    $stmt = $mysqli->prepare("SELECT actif FROM utilisateurs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($actif);
    $stmt->fetch();
    $stmt->close();

    // Inversion de l'état
    $new_state = $actif ? 0 : 1;
    $action = $actif ? "Désactivation" : "Réactivation";

    $stmt = $mysqli->prepare("UPDATE utilisateurs SET actif = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_state, $id);
    if ($stmt->execute()) {
      log_action($admin_id, "$action de l'utilisateur", "utilisateurs", $id);
      header("Location: liste_utilisateur.php?etat=$new_state");
    } else {
      echo "Erreur lors de la mise à jour.";
    }
    $stmt->close();
  ?>