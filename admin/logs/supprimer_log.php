  <?php
    require_once '../../includes/db.php';
    require_once '../../includes/fonctions.php';
    session_start();

    // Vérification du rôle


    $id = $_GET['id'] ?? null;
    if (!$id || !is_numeric($id)) {
      die("ID invalide.");
    }

    // Suppression du log
    $stmt = $mysqli->prepare("DELETE FROM logs_actions WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
      
      log_action($_SESSION['admin_id']['id'], "Suppression du log #$id", "logs_actions", $id);
      header("Location: liste_logs.php?msg=supprime");
      exit;
    } 
    else {
      echo "Erreur lors de la suppression : " . $stmt->error;
    }