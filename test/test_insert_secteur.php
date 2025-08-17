<?php
require_once '../includes/db.php'; // Connexion MySQLi via $conn

function generer_slug(string $texte): string {
    $texte = trim($texte);
    $texte = strtolower($texte);
    $texte = iconv('UTF-8', 'ASCII//TRANSLIT', $texte);
    $texte = preg_replace('/[^a-z0-9\s-]/', '', $texte);
    $texte = preg_replace('/[\s-]+/', '-', $texte);
    $texte = trim($texte, '-');
    return empty($texte) ? strtolower(uniqid()) : $texte;
}

// Liste des secteurs avec descriptions précises
$secteurs = [
    "Agriculture" => "Ensemble des activités liées à la culture des sols, à l’élevage, à la transformation des produits agricoles et à la sécurité alimentaire.",
    "Éducation" => "Système d’enseignement formel et informel, incluant les écoles, universités, formations professionnelles et initiatives d’alphabétisation.",
    "Santé" => "Services médicaux, hôpitaux, cliniques, pharmacies, prévention sanitaire et initiatives de santé publique.",
    "Transport" => "Infrastructure et services liés au déplacement de personnes et de marchandises : routes, véhicules, logistique, mobilité urbaine.",
    "Énergie" => "Production, distribution et gestion des ressources énergétiques : électricité, gaz, solaire, biomasse, etc.",
    "Technologie" => "Secteur dédié à l’innovation numérique : développement logiciel, télécommunications, IA, cybersécurité, électronique.",
    "Commerce" => "Activités de vente, distribution, import-export, marchés locaux, e-commerce et gestion des flux commerciaux.",
    "Finance" => "Services bancaires, microfinance, assurances, comptabilité, investissements et gestion des risques économiques.",
    "Tourisme" => "Promotion et gestion des activités touristiques : hébergement, restauration, patrimoine culturel, écotourisme.",
    "Artisanat" => "Production manuelle et locale d’objets utilitaires ou décoratifs : textile, poterie, sculpture, bijouterie, etc.",
    "Immobilier" => "Construction, vente, location et gestion de biens immobiliers résidentiels, commerciaux ou industriels.",
    "Environnement" => "Protection des ressources naturelles, gestion des déchets, reforestation, écologie urbaine et développement durable."
];

$mysqli->begin_transaction();

try {
    $stmt = $mysqli->prepare("INSERT INTO secteurs (nom, slug, description, ordre) VALUES (?, ?, ?, ?)");

    $ordre = 1;
    foreach ($secteurs as $nom => $description) {
        $slug = generer_slug($nom);
        $stmt->bind_param("sssi", $nom, $slug, $description, $ordre);
        $stmt->execute();
        $ordre++;
    }

    $mysqli->commit();
    echo "Insertion des secteurs avec descriptions réussie.";
} catch (Exception $e) {
    $mysqli->rollback();
    echo "Erreur lors de l'insertion : " . $e->getMessage();
}

$stmt->close();
$mysqli->close();