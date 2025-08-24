<?php
require_once '../includes/db.php';          // Connexion via $mysqli
require_once '../includes/fonctions.php';   // Fonctions utilitaires (dont generer_slug)
require_once '../includes/constants.php';   // Constantes globales (dont DEFAULT_STATUT_ENTREPRISE)

// 🔧 Vérification de la fonction slug
if (!function_exists('generer_slug')) {
    function generer_slug(string $texte): string {
        $texte = trim($texte);
        $texte = strtolower($texte);
        $texte = iconv('UTF-8', 'ASCII//TRANSLIT', $texte);
        $texte = preg_replace('/[^a-z0-9\s-]/', '', $texte);
        $texte = preg_replace('/[\s-]+/', '-', $texte);
        $texte = trim($texte, '-');
        return empty($texte) ? strtolower(uniqid()) : $texte;
    }
}

// 🔍 Données de Light Innovation
$nom          = "Light Innovation";
$slug         = generer_slug($nom);
$email        = "talk@light.bj";
$tel          = "0167406640";
$adresse      = "Parakou, 1ère rue à droite après le stade";
$quartier     = "Ladjifarani";
$localisation = "https://www.google.com/maps?q=Light+Innovation+Parakou+Ladjifarani";
$logo         = ""; // À intégrer plus tard si disponible
$statut       = DEFAULT_STATUT_ENTREPRISE;

// 🧠 Description réaliste
$description = "$nom est une agence de communication corporate existante depuis 2016 et basée au Bénin. Nous vous proposons nos services en création graphique, en développement web et mobile et en communication digitale.";

// 🔎 Récupération du secteur "Technologie"
$res = $mysqli->prepare("SELECT id FROM secteurs WHERE slug = ?");
$slug_secteur = generer_slug("Technologie");
$res->bind_param("s", $slug_secteur);
$res->execute();
$res->bind_result($secteur_id);
$res->fetch();
$res->close();

if (empty($secteur_id)) {
    die("❌ Secteur 'Technologie' introuvable dans la base.");
}

// 🔐 Vérification d'existence
$verif = $mysqli->prepare("SELECT COUNT(*) FROM entreprises WHERE slug = ?");
$verif->bind_param("s", $slug);
$verif->execute();
$verif->bind_result($count);
$verif->fetch();
$verif->close();

if ($count > 0) {
    echo "ℹ️ Light Innovation existe déjà dans la base.";
    exit;
}

// ✅ Insertion
$stmt = $mysqli->prepare("
    INSERT INTO entreprises 
    (nom, slug, email_contact, telephone, secteur_id, adresse, quartier, localisation, description, logo, statut) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param("ssssissssss", 
    $nom, $slug, $email, $tel, $secteur_id, $adresse, $quartier, $localisation, $description, $logo, $statut
);

$stmt->execute();
$stmt->close();
$mysqli->close();

echo "✅ Light Innovation a été insérée avec succès.";
?>