<?php
include_once '../includes/db.php';
include_once '../includes/constants.php';
include_once '../includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nom = trim($_POST['nom']);
  $email = trim($_POST['email']);
  $sujet = trim($_POST['sujet']);
  $message = trim($_POST['message']);

  // Enregistrement
  $stmt = $mysqli->prepare("INSERT INTO messages_contact (nom, email, sujet, message) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $nom, $email, $sujet, $message);
  $stmt->execute();

  // Notification
  $contenu = "<p><strong>Nom :</strong> $nom<br><strong>Email :</strong> $email<br><strong>Sujet :</strong> $sujet</p><p>$message</p>";
  envoyer_notification(EMAIL_ADMIN, "Nouveau message de contact", $contenu);

  header("Location: /ecoparakou/public/contact.php?success=1");
  exit;
}