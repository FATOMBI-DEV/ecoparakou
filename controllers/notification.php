<?php
include_once '../includes/db.php';

function enregistrer_notification($entreprise_id, $type, $message) {
  global $mysqli;
  $stmt = $mysqli->prepare("INSERT INTO notifications (entreprise_id, type, message) VALUES (?, ?, ?)");
  $stmt->bind_param("iss", $entreprise_id, $type, $message);
  $stmt->execute();
}