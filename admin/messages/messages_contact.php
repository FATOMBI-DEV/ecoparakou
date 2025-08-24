  <?php
    require_once '../../includes/db.php';
    require_once '../../includes/session.php';
    $page_title = " Messages reçus";

    // Marquage comme traité
    if (isset($_GET['traite']) && is_numeric($_GET['traite'])) {
        $id = intval($_GET['traite']);
        $mysqli->query("UPDATE messages_contact SET traite = 1 WHERE id = $id");
        header("Location: messages_contact.php");
        exit;
    }

    // Recherche
    $search = isset($_GET['q']) ? trim($_GET['q']) : '';
    $search_sql = $search ? "WHERE (nom LIKE '%$search%' OR email LIKE '%$search%' OR sujet LIKE '%$search%')" : '';

    // Récupération des messages
    $messages = $mysqli->query("SELECT id, nom, email, sujet, message, date_envoi, traite
      FROM messages_contact
      $search_sql
      ORDER BY date_envoi DESC
    ");
  ?>

  <!DOCTYPE html>
  <html lang="fr">
    <head>
      <?php include_once '../../includes/meta-head.php'; ?>
      <link rel="stylesheet" href="../../public/assets/css/messages_contacts.css">
      <link rel="stylesheet" href="../../public/assets/css/header.css">
      <link rel="stylesheet" href="../../public/assets/css/footer.css">
    </head>

    <body>
      <?php include '../../includes/header.php'; ?>

      <main>
        <div class="container py-5">
          <h2 class="mb-4 text-primary text-center pt-4"><i class="bi bi-envelope-fill"></i> Messages reçus</h2>

          <form method="get" class="input-group mb-4">
            <input type="text" name="q" class="form-control" placeholder="Rechercher par nom, email ou sujet..." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-outline-primary" type="submit">
              <i class="bi bi-search"></i>
            </button>
          </form>

          <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white shadow-sm">
              <thead class="table-primary">
                <tr>
                  <th><i class="bi bi-person-fill"></i> Nom</th>
                  <th><i class="bi bi-envelope"></i> Email</th>
                  <th><i class="bi bi-chat-left-text"></i> Sujet</th>
                  <th><i class="bi bi-file-text"></i> Message</th>
                  <th><i class="bi bi-calendar-event"></i> Date</th>
                  <th><i class="bi bi-check-circle"></i> Statut</th>
                  <th><i class="bi bi-gear"></i> Action</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($msg = $messages->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($msg['nom']) ?></td>
                    <td><a href="mailto:<?= htmlspecialchars($msg['email']) ?>" class="text-decoration-none"><?= htmlspecialchars($msg['email']) ?></a></td>
                    <td><?= htmlspecialchars($msg['sujet']) ?></td>
                    <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($msg['date_envoi'])) ?></td>
                    <td>
                      <?php if ($msg['traite']): ?>
                        <span class="badge bg-success"><i class="bi bi-check-lg"></i> Traité</span>
                      <?php else: ?>
                        <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Non traité</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if (!$msg['traite']): ?>
                        <a href="?traite=<?= $msg['id'] ?>" class="btn btn-sm btn-outline-success icon-btn">
                          <i class="bi bi-check2-circle"></i> Traiter
                        </a>
                      <?php else: ?>
                        <span class="text-muted"><i class="bi bi-check-circle-fill"></i></span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </main>
      <?php include '../../includes/footer.php'; ?>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
      <script>
        document.addEventListener("DOMContentLoaded", () => {
          document.querySelectorAll("tbody tr").forEach((row, i) => {
            row.style.opacity = 0;
            row.style.transform = "translateY(10px)";
            setTimeout(() => {
              row.style.transition = "all 0.4s ease";
              row.style.opacity = 1;
              row.style.transform = "translateY(0)";
            }, 80 * i);
          });
        });
      </script>
    </body>
  </html>