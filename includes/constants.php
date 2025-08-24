<?php

// === Informations générales ===
define('SITE_NAME', 'EcoParakou');
define('SITE_DESCRIPTION', 'Annuaire des entreprises de Parakou');
define('SITE_AUTHOR', 'Stagiaires-Light');


// === Chemins ===
define('ROOT_PATH', dirname(__DIR__));
define('ASSETS_PATH', '/ecoparakou/public/assets'); 
define('UPLOADS_PATH', '/public/uploads'); // dossier des logos

// === Couleurs du thème 
define('COLOR_PRIMARY', '#1F2A44');     // Bleu nuit
define('COLOR_ACCENT', '#FF9800');      // Orange local
define('COLOR_BG', '#F5F1EB');          // Beige sable
define('COLOR_TEXT', '#333333');        // Texte principal

// === Email de contact principal ===
define('EMAIL_ADMIN', 'akomedi533@gmail.com');

define('BASE_URL', 'http://192.168.201.74/ecoparakou/');

// === Paramètres techniques ===
define('MAX_LOGO_SIZE_MB', 2); // taille max du logo en Mo
define('DEFAULT_STATUT_ENTREPRISE', 'en_attente'); // statut par défaut à l’inscription

