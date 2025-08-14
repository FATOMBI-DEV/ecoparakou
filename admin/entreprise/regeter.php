<?php
include_once '../../includes/db.php';
include_once '../../includes/session.php';
include_once '../../includes/mailer.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
  $stmt = $mysqli->prepare("UPDATE entreprises SET statut = 'rejete', modifie_par = ? WHERE id = ?");
  $stmt->bind_param("ii", $_SESSION['user_id'], $id);
  $stmt->execute();

  $res = $mysqli->query("SELECT email_contact FROM entreprises WHERE id = $id");
  $e = $res->fetch_assoc();

  $msg = "<p>Votre demande a été rejetée. Vous pouvez la modifier et la soumettre à nouveau.</p>";
  envoyer_notification($e['email_contact'], "Rejet de votre entreprise", $msg);

  header("Location: liste.php?rejet=1");
  exit;
}