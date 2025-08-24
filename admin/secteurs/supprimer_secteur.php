    <?php
        session_start();
        include_once '../../includes/db.php';
        include_once '../../includes/fonctions.php';

        header('Content-Type: text/plain');

        if (!isset($_SESSION['admin_id'])) {
            http_response_code(403);
            echo "Accès refusé.";
            exit;
        }

        $admin_id = $_SESSION['admin_id'];

        // Vérification du rôle
        $stmt = $mysqli->prepare("SELECT role FROM utilisateurs WHERE id = ?");
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $stmt->bind_result($role);
        $stmt->fetch();
        $stmt->close();

        if ($role !== 'admin') {
            http_response_code(403);
            echo "Seuls les administrateurs peuvent supprimer un secteur.";
            exit;
        }

        // Récupération de l'ID
        $id_secteur = intval($_POST['id'] ?? 0);
        if ($id_secteur <= 0) {
            http_response_code(400);
            echo "ID invalide.";
            exit;
        }

        // Récupération du nom du secteur
        $stmt = $mysqli->prepare("SELECT nom FROM secteurs WHERE id = ?");
        $stmt->bind_param("i", $id_secteur);
        $stmt->execute();
        $stmt->bind_result($nom_secteur);

        if (!$stmt->fetch()) {
            http_response_code(404);
            echo "Secteur introuvable.";
            exit;
        }
        $stmt->close();

        // Suppression du secteur (les entreprises liées seront supprimées automatiquement si ON DELETE CASCADE est actif)
        $stmt = $mysqli->prepare("DELETE FROM secteurs WHERE id = ?");
        $stmt->bind_param("i", $id_secteur);

        if ($stmt->execute()) {
            log_action($admin_id, "Suppression du secteur : $nom_secteur", "secteurs", $id_secteur);
            echo "success";
        } else {
            http_response_code(500);
            echo "Erreur lors de la suppression.";
        }
        $stmt->close();
    ?>