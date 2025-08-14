<?php
include_once '../includes/db.php';
include_once '../includes/constants.php';
include_once '../includes/mailer.php';
include_once '../includes/session.php';

$id = intval($_GET['id'] ?? 0);
$action = $_GET['action'] ?? 'valider';

if ($id > 0 && in_array($action, ['valider', 'rejeter'])) {
  $statut = $action === 'valider' ? 'valide' : 'rejete';

  // Mise à jour
  $stmt = $mysqli->prepare("UPDATE entreprises SET statut = ?, date_validation = NOW(), modifie_par = ? WHERE id = ?");
  $stmt->bind_param("sii", $statut, $_SESSION['user_id'], $id);
  $stmt->execute();

  // Récupérer email
  $res = $mysqli->query("SELECT email_contact FROM entreprises WHERE id = $id");
  $e = $res->fetch_assoc();

  // Notification
  $msg = $statut === 'valide'
    ? "<p>Votre entreprise a été validée et est désormais visible sur " . SITE_NAME . ".</p>"
    : "<p>Votre demande a été rejetée. Vous pouvez la modifier et la soumettre à nouveau.</p>";

  envoyer_notification($e['email_contact'], "Mise à jour de votre demande", $msg);

  header("Location: /admin/entreprises/liste.php?success=1");
  exit;
}