<?php
include_once '../../includes/db.php';

$secteurs = [
  ['Agroalimentaire', 'Production et transformation locale'],
  ['BTP', 'Construction, matériaux, génie civil'],
  ['Santé', 'Pharmacies, cliniques, laboratoires'],
  ['Éducation', 'Écoles, formations, soutien scolaire'],
  ['Technologie', 'Développement, télécoms, services IT'],
  ['Commerce', 'Boutiques, marchés, distribution'],
];

foreach ($secteurs as $index => [$nom, $desc]) {
  $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $nom));
  $ordre = $index + 1;
  $stmt = $mysqli->prepare("INSERT INTO secteurs (nom, slug, description, ordre) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("sssi", $nom, $slug, $desc, $ordre);
  $stmt->execute();
}

echo "Secteurs ajoutés avec succès.";