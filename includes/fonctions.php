<?php
// functions.php

function generer_slug(string $texte): string {
    $slug = strtolower($texte);
    $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug);
    $slug = preg_replace('/[^a-z0-9\- ]/', '', $slug);
    $slug = str_replace(' ', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}

function envoyer_email($to, $sujet, $message) {
    $headers = "From: " . EMAIL_ADMIN . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    return mail($to, $sujet, $message, $headers);
}

function enregistrer_action($utilisateur_id, $action, $table, $cible_id) {
  global $mysqli;
  $stmt = $mysqli->prepare("INSERT INTO logs_actions (utilisateur_id, action, table_cible, cible_id) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("issi", $utilisateur_id, $action, $table, $cible_id);
  $stmt->execute();
}