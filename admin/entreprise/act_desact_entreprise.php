  <?php
    session_start();
    include_once '../../includes/db.php';
    include_once '../../includes/fonctions.php';
    include_once '../../includes/mailer.php';

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

    if ($role !== 'admin') {
      echo "Accès refusé.";
      exit;
    }

    // Vérification de l'ID entreprise
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id']) || !is_numeric($_POST['id'])) {
      echo "ID invalide.";
      exit;
    }

    $entreprise_id = intval($_POST['id']);
    $motif = trim($_POST['motif'] ?? '');

    // Récupération du statut actuel
    $stmt = $mysqli->prepare("SELECT statut, email_contact, nom FROM entreprises WHERE id = ?");
    $stmt->bind_param("i", $entreprise_id);
    $stmt->execute();
    $stmt->bind_result($statut_actuel, $email_contact, $nom_entreprise);
    $stmt->fetch();
    $stmt->close();

    if (!$statut_actuel || !$email_contact) {
      echo "Entreprise introuvable ou email manquant.";
      exit;
    }

    // Détermination du nouveau statut
    if ($statut_actuel === 'valide') {
      $nouveau_statut = 'suspendu';
      $type = 'suspension';
      $action = "Suspension de l'entreprise";
      $sujet = "Suspension de votre entreprise sur Eco Parakou";
      $message = "
        <h3>Bonjour {$nom_entreprise},</h3>
        <p>Votre entreprise a été suspendue temporairement sur EcoParakou.</p>
        <p><strong>Motif :</strong> " . ($motif ?: "vérification ou non-conformité") . "</p>
        <p><strong>Date :</strong> " . date('d/m/Y - H:i') . "</p>
        <p>Vous pouvez nous contacter pour plus d’informations.</p>
        <br>
        <p>— Equipe EcoParakou</p>
        ";
      envoyer_notification($email_contact, $sujet, $message);

    } 
    elseif ($statut_actuel === 'suspendu') {
      $nouveau_statut = 'valide';
      $type = 'reactivation';
      $action = "Réactivation de l'entreprise";
      $sujet = "Réactivation de votre entreprise sur Eco Parakou";
      $message = "
        <h3>Bonjour {$nom_entreprise},</h3>
        <p>Votre entreprise a été réactivée avec succès.</p>
        <p><strong>Date :</strong> " . date('d/m/Y - H:i') . "</p>
        <p>Elle est désormais visible sur la plateforme Eco Parakou.</p>
        <br>
        <p>— Equipe EcoParakou</p>
        ";
      envoyer_notification($email_contact, $sujet, $message);

    }
    else {
      echo "Statut non modifiable.";
      exit;
    }

    // Mise à jour du statut
    $stmt = $mysqli->prepare("UPDATE entreprises SET statut = ?, modifie_par = ? WHERE id = ?");
    $stmt->bind_param("sii", $nouveau_statut, $admin_id, $entreprise_id);
    $stmt->execute();
    $stmt->close();

    // Notification
    $stmt = $mysqli->prepare("INSERT INTO notifications (entreprise_id, type, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $entreprise_id, $type, $message);
    $stmt->execute();
    $stmt->close();

    // Envoi email
    envoyer_email($email_contact, $sujet, $message);

    // Log
    log_action($admin_id, "$action : $motif", "entreprises", $entreprise_id);

    
    header("Location: liste_entreprise.php?success=" . urlencode("Statut mis à jour."));
    exit;