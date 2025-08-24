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
        $stmt = $mysqli->prepare("SELECT l.*, u.nom AS utilisateur
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
            <main class="flex-grow-1">
                <div class="container py-5">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h3 class="mb-0">Détail d'action #<?= $log['id'] ?></h3>
                        </div>

                        <div class="card-body">
                            <table class="table table-striped table-bordered mb-4">
                                <tbody>
                                    <tr>
                                        <th scope="row">ID</th>
                                        <td><?= $log['id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Utilisateur</th>
                                        <td><?= htmlspecialchars($log['utilisateur'] ?? '—') ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Action</th>
                                        <td><?= htmlspecialchars($log['action']) ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Table cible</th>
                                        <td><?= htmlspecialchars($log['table_cible']) ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">ID cible</th>
                                        <td><?= $log['cible_id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Date</th>
                                        <td><?= date('d/m/Y H:i:s', strtotime($log['date_action'])) ?></td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="d-flex gap-2">
                                <?php if ($admin['role'] === 'admin'): ?>
                                    <a href="supprimer_log.php?id=<?= $log['id'] ?>" class="btn btn-danger"
                                    onclick="return confirm('Confirmer la suppression de ce log ?')">
                                        Supprimer
                                    </a>
                                <?php endif; ?>
                                <a href="liste_logs.php" class="btn btn-secondary">← Retour à la liste</a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include_once '../../includes/footer.php'; ?>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        </body>
    </html>