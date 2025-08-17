<?php
require_once '../includes/db.php';
require_once '../includes/fonctions.php';
require_once '../includes/constants.php';

// Fonction de description réaliste par secteur
function generer_description_detaillee($nom, $secteur_id) {
  $descriptions = [
    1 => "$nom accompagne les agriculteurs de la région avec des outils modernes de gestion des cultures, de distribution et de transformation agroalimentaire.",
    2 => "$nom propose des formations innovantes en ligne et en présentiel, adaptées aux besoins des jeunes et des professionnels de Parakou.",
    3 => "$nom offre des services de santé de proximité, avec des consultations spécialisées, un laboratoire moderne et une pharmacie intégrée.",
    4 => "$nom est spécialisée dans le transport de marchandises et de passagers, avec une flotte moderne et des itinéraires optimisés.",
    5 => "$nom développe des solutions énergétiques durables, notamment dans le solaire, le biogaz et l’optimisation des réseaux locaux.",
    6 => "$nom conçoit des outils numériques sur mesure : sites web, applications mobiles, automatisation et cybersécurité.",
    7 => "$nom est un acteur du commerce local, avec des points de vente physiques et une plateforme e-commerce pour les produits béninois.",
    8 => "$nom propose des services financiers accessibles : microcrédit, assurance, gestion de portefeuille et accompagnement comptable.",
    9 => "$nom valorise le patrimoine touristique de Parakou à travers des circuits guidés, des hébergements et des expériences culturelles.",
    10 => "$nom fabrique et commercialise des objets artisanaux uniques : textiles, poteries, bijoux et décorations traditionnelles.",
    11 => "$nom intervient dans l’immobilier résidentiel et commercial, avec des offres de location, vente et gestion locative.",
    12 => "$nom agit pour la préservation de l’environnement : recyclage, reboisement, sensibilisation et gestion des déchets."
  ];
  return $descriptions[$secteur_id] ?? "$nom est une entreprise active dans son secteur, offrant des services adaptés aux besoins locaux.";
}

// Liste des noms d'entreprises
$entreprises = [
  "AgroParakou", "TechNova", "École Horizon", "Clinique Lumière", "TransBenin",
  "SolarForce", "WebXpert", "Marché Central", "MicroFinance Plus", "Tourisme Nord",
  "Artisanat Soleil", "ImmoParakou", "GreenLife", "AgroPlus", "EduSmart",
  "Santé Express", "LogiTrack", "Énergie Verte", "CodeLab", "Boutique Élite",
  "AssurBenin", "Voyage Évasion", "CréaMain", "LogisPro", "EcoNature",
  "AgriTech", "Campus Connect", "PharmaCare", "MobilityX", "PowerGrid",
  "DevStudio", "CommerceLink"
];

// Récupération des secteurs
$secteurs = [];
$res = $mysqli->query("SELECT id FROM secteurs ORDER BY id ASC");
while ($row = $res->fetch_assoc()) {
  $secteurs[] = $row['id'];
}

// Répartition non égale des entreprises
$repartition = array_merge(
  array_fill(0, 5, $secteurs[0]),   // Agriculture
  array_fill(0, 4, $secteurs[1]),   // Éducation
  array_fill(0, 4, $secteurs[2]),   // Santé
  array_fill(0, 3, $secteurs[3]),   // Transport
  array_fill(0, 3, $secteurs[4]),   // Énergie
  array_fill(0, 3, $secteurs[5]),   // Technologie
  array_fill(0, 2, $secteurs[6]),   // Commerce
  array_fill(0, 2, $secteurs[7]),   // Finance
  array_fill(0, 2, $secteurs[8]),   // Tourisme
  array_fill(0, 1, $secteurs[9]),   // Artisanat
  array_fill(0, 2, $secteurs[10]),  // Immobilier
  array_fill(0, 1, $secteurs[11])   // Environnement
);

// Sécurité : vérification
if (count($entreprises) !== count($repartition)) {
  die("❌ Erreur : le nombre d'entreprises ne correspond pas à la répartition des secteurs.");
}

// Préparation de la requête
$stmt = $mysqli->prepare("
  INSERT INTO entreprises 
  (nom, slug, email_contact, telephone, secteur_id, adresse, quartier, localisation, description, logo, statut) 
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$statut = DEFAULT_STATUT_ENTREPRISE;
$quartiers = ["Depot", "Zongo", "Albarika", "Wansirou", "Kpébié"];
$localisation = "";
$logo = "";

foreach ($entreprises as $i => $nom) {
  $slug = generer_slug($nom);
  $email = strtolower($slug) . "@test.bj";
  $tel = "0151967" . str_pad($i, 3, "0", STR_PAD_LEFT);
  $secteur_id = $repartition[$i];
  $adresse = "Parakou, Bénin";
  $quartier = $quartiers[$i % count($quartiers)];
  $description = generer_description_detaillee($nom, $secteur_id);

  $stmt->bind_param("ssssissssss", $nom, $slug, $email, $tel, $secteur_id, $adresse, $quartier, $localisation, $description, $logo, $statut);
  $stmt->execute();
}

$stmt->close();
$mysqli->close();

echo "✅ 32 entreprises ont été insérées avec des descriptions réalistes.";