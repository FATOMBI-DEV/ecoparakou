<?php
include_once '../includes/db.php';

$nom = 'Akomedi';
$email = 'akomedi533@gmail.com';
$motdepasse = password_hash('fam', PASSWORD_DEFAULT);
$role = 'admin';
$actif = true;

$stmt = $mysqli->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role, actif, date_creation) VALUES (?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("ssssi", $nom, $email, $motdepasse, $role, $actif);
$stmt->execute();
$stmt->close();

echo "Admin initial créé avec succès.";