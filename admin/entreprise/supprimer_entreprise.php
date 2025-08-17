<?php
session_start();
include_once '../../includes/db.php';
include_once '../../includes/fonctions.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: ../login.php");
  exit;
}

$admin_id = $_SESSION['admin_id'];

// 🔐 Vérification du rôle
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

// 📥 Vérification des données
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id']) || !is_numeric($_POST['id'])) {
  echo "ID invalide.";
  exit;
}

$entreprise_id = intval($_POST['id']);
$motif = trim($_POST['motif'] ?? '');

if (empty($motif)) {
  echo "Motif requis.";
  exit;
}

// 📦 Récupération des infos entreprise
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

// 🗑️ Suppression
$stmt = $mysqli->prepare("DELETE FROM entreprises WHERE id = ?");
$stmt->bind_param("i", $entreprise_id);
$stmt->execute();
$stmt->close();

// 🔔 Notification
$message = "Votre entreprise a été supprimée définitivement. Motif : $motif";
$stmt = $mysqli->prepare("INSERT INTO notifications (entreprise_id, type, message) VALUES (?, 'suppression', ?)");
$stmt->bind_param("is", $entreprise_id, $message);
$stmt->execute();
$stmt->close();

// 📩 Email
$sujet = "Suppression de votre entreprise sur Eco Parakou";
$contenu = "Bonjour,\n\nVotre entreprise « $nom_entreprise » a été supprimée définitivement de la plateforme.\n\nMotif : $motif\n\nPour toute question, contactez l’équipe Eco Parakou.\n\nCordialement,\nL’équipe Eco Parakou";
envoyer_email($email_contact, $sujet, $contenu);

// 🧾 Log
log_action($admin_id, "Suppression entreprise #$entreprise_id : $motif", "entreprises", $entreprise_id);

// ✅ Redirection
header("Location: liste_entreprise.php?success=" . urlencode("Entreprise supprimée."));
exit;