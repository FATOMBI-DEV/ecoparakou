<?php
include_once '../includes/db.php';
include_once '../includes/fonctions.php';
include_once '../includes/constants.php';
include_once '../includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Nettoyage
  $nom = trim($_POST['nom']);
  $slug = generer_slug($nom);
  $email = trim($_POST['email_contact']);
  $tel = trim($_POST['telephone']);
  $secteur_id = intval($_POST['secteur_id']);
  $adresse = trim($_POST['adresse']);
  $quartier = trim($_POST['quartier']);
  $localisation = trim($_POST['localisation']);
  $description = trim($_POST['description']);
  $statut = DEFAULT_STATUT_ENTREPRISE;

  // Logo
  $logo = '';
  if (!empty($_FILES['logo']['name'])) {
    $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    if (in_array($ext, $allowed)) {
      $logo = $slug . '.' . $ext;
      move_uploaded_file($_FILES['logo']['tmp_name'], '../public/uploads/' . $logo);
    }
  }

  // Préparation
  $stmt = $mysqli->prepare("
    INSERT INTO entreprises 
    (nom, slug, email_contact, telephone, secteur_id, adresse, quartier, localisation, description, logo, statut) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
  ");

  // Variables à passer par référence
  $stmt->bind_param(  "ssssissssss",  $nom,  $slug,  $email,  $tel,  $secteur_id,  $adresse,  $quartier,  $localisation,  $description,  $logo,  $statut);

  $stmt->execute();

  // Email confirmation
  $message = "
    <p>Bonjour,</p>
    <p>Votre demande d'inscription pour <strong>$nom</strong> a bien été reçue.</p>
    <p>Elle est en cours de validation par notre équipe.</p>
    <p>Merci pour votre confiance,<br><strong>" . SITE_NAME . "</strong></p>
  ";
  envoyer_notification($email, "Demande reçue - " . SITE_NAME, $message);

  header("Location: /ecoparakou/public/inscription.php?success=1");
exit;
}