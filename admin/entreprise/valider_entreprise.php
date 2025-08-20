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

// Vérification de l'ID
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id']) || !is_numeric($_POST['id'])) {
  echo "ID invalide.";
  exit;
}

$entreprise_id = intval($_POST['id']);

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
$stmt = $mysqli->prepare("UPDATE entreprises SET statut = 'valide', date_validation = NOW(), modifie_par = ?, motif_rejet = NULL, motif_suspension = NULL WHERE id = ?");
$stmt->bind_param("ii", $admin_id, $entreprise_id);
$stmt->execute();
$stmt->close();

// Notification
$message = "Votre entreprise « $nom_entreprise » a été validée avec succès.";
$stmt = $mysqli->prepare("INSERT INTO notifications (entreprise_id, type, message) VALUES (?, 'validation', ?)");
$stmt->bind_param("is", $entreprise_id, $message);
$stmt->execute();
$stmt->close();

// Email
$sujet = "Validation de votre entreprise sur ";
$contenu = "<h3>Bonjour {$nom_entreprise},</h3>
  <p>Votre entreprise a été validée avec succès sur EcoParakou.</p>
  <p><strong>Date :</strong> " . date('d/m/Y - H:i') . "</p>
  <p>Elle est désormais visible sur la plateforme Eco Parakou. Mercie votre confiance.</p>
  <br>
  <p>— Equipe EcoParakou</p>
  ";

envoyer_notification($email_contact, $sujet . SITE_NAME, $contenu);

log_action($admin_id, "Validation entreprise #$entreprise_id", "entreprises", $entreprise_id);


header("Location: liste_entreprise.php?success=" . urlencode("Entreprise validée."));
exit;