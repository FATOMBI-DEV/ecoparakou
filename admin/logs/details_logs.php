    <?php
        require_once '../../includes/db.php';
        require_once '../../includes/fonctions.php';
        session_start();
        $page_title = "Détail du log";

        if (!isset($_SESSION['admin_id'])) {
        header("Location: ../login.php");
        exit;
        }

        $stmt = $mysqli->prepare("SELECT role FROM utilisateurs WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['admin_id']);
        $stmt->execute();
        $admin = $stmt->get_result()->fetch_assoc();

        // Récupération de l'ID
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
        die("ID invalide.");
        }

        // Requête du log
        $stmt = $mysqli->prepare("
        SELECT l.*, u.nom AS utilisateur
        FROM logs_actions l
        LEFT JOIN utilisateurs u ON u.id = l.utilisateur_id
        WHERE l.id = ?
        LIMIT 1
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $log = $result->fetch_assoc();

        if (!$log) {
        die("Log introuvable.");
        }
    ?>

    <!DOCTYPE html>
    <html lang="fr">
        <head>
            <?php include_once '../../includes/meta-head.php'; ?>
            <link rel="stylesheet" href="../../public/assets/css/details_logs.css">
            <link rel="stylesheet" href="../../public/assets/css/header.css">
            <link rel="stylesheet" href="../../public/assets/css/footer.css">
        </head>
        <body>
            <?php include_once '../../includes/header.php'; ?>
            <main>
                <div class="container mt-4">
                    <h3 class="mb-4 text-primary">Détail du log #<?= $log['id'] ?></h3>

                    <table class="table table-bordered bg-white">
                        <tr><th>ID</th><td><?= $log['id'] ?></td></tr>
                        <tr><th>Utilisateur</th><td><?= htmlspecialchars($log['utilisateur'] ?? '—') ?></td></tr>
                        <tr><th>Action</th><td><?= htmlspecialchars($log['action']) ?></td></tr>
                        <tr><th>Table cible</th><td><?= htmlspecialchars($log['table_cible']) ?></td></tr>
                        <tr><th>ID cible</th><td><?= $log['cible_id'] ?></td></tr>
                        <tr><th>Date</th><td><?= date('d/m/Y H:i:s', strtotime($log['date_action'])) ?></td></tr>
                    </table>

                    <?php if ($admin['role'] === 'admin'): ?>
                        <a href="supprimer_log.php?id=<?= $log['id'] ?>" class="btn btn-danger"
                            onclick="return confirm('Confirmer la suppression de ce log ?')">
                            Supprimer
                        </a>
                    <?php endif; ?>
                    <a href="liste_logs.php" class="btn btn-secondary">← Retour à la liste</a>
                </div>
            </main>
            <?php include_once '../../includes/footer.php'; ?>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        </body>
    </html>