  <?php
    session_start();
    include_once '../../includes/db.php';
    include_once '../../includes/fonctions.php';

    if (!isset($_SESSION['admin_id'])) {
      header("Location: ../login.php");
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

    if (!in_array($role, ['admin', 'moderateur'])) {
      echo "Accès refusé.";
      exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id']) || !is_numeric($_POST['id'])) {
      echo "Requête invalide.";
      exit;
    }

    $entreprise_id = intval($_POST['id']);
    $motif_rejet = trim($_POST['motif'] ?? '');

    if (empty($motif_rejet)) {
      echo "Motif de rejet requis.";
      exit;
    }

    // Récupération des infos entreprise
    $stmt = $mysqli->prepare("SELECT nom, email_contact FROM entreprises WHERE id = ?");
    $stmt->bind_param("i", $entreprise_id);
    $stmt->execute();
    $stmt->bind_result($nom_entreprise, $email_contact);
    $stmt->fetch();
    $stmt->close();

    if (!$nom_entreprise || !$email_contact) {
      echo "Entreprise introuvable ou email manquant.";
      exit;
    }

    // Mise à jour du statut
    $stmt = $mysqli->prepare("UPDATE entreprises SET statut = 'rejete', motif_rejet = ?, modifie_par = ? WHERE id = ?");
    $stmt->bind_param("sii", $motif_rejet, $admin_id, $entreprise_id);
    $stmt->execute();
    $stmt->close();

    // Notification
    $message = "Votre entreprise a été rejetée. Motif : $motif_rejet";
    $stmt = $mysqli->prepare("INSERT INTO notifications (entreprise_id, type, message) VALUES (?, 'rejet', ?)");
    $stmt->bind_param("is", $entreprise_id, $message);
    $stmt->execute();
    $stmt->close();

    // Envoi email
    $sujet = "Rejet de votre entreprise sur ";
    $contenu = "<h3>Bonjour {$nom_entreprise},</h3>
      <p>Votre entreprise a été rejetée sur EcoParakou.</p>
      <p><strong>Motif :</strong> " . ($motif_rejet ?: "vérification ou non-conformité") . "</p>
      <p><strong>Date :</strong> " . date('d/m/Y - H:i') . "</p>
      <p>Vous pouvez soumettre une nouvelle demande après correction ou nous contacter pour plus d’informations.</p>
      <br>
      <p>— Equipe EcoParakou</p>
      ";
    //envoyer_email($email_contact, $sujet, $contenu);

    envoyer_notification($email_contact, $sujet . SITE_NAME, $contenu);

    log_action($admin_id, "Rejet d entreprise #$entreprise_id", "entreprises", $entreprise_id);

    header("Location: liste_entreprise.php?success=" . urlencode("Entreprise rejetée avec motif."));
    exit;