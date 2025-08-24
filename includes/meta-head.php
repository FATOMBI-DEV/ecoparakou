  <?php
    include_once __DIR__ . '/constants.php';
  ?>
  <!DOCTYPE html>
  <html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Meta Tags -->
    <meta name="description" content="<?= SITE_DESCRIPTION ?>">
    <meta name="author" content="<?= SITE_AUTHOR ?>">
    <meta name="keywords" content="annuaire, entreprises, local, <?= SITE_NAME ?>, <?= SITE_DESCRIPTION ?>">
    <meta name="robots" content="index, follow">
    <meta name="language" content="fr">
    <meta name="theme-color" content="#ffffff">

    <!-- Open Graph / Facebook -->
    <meta property="og:title" content="<?= SITE_NAME ?> | <?= $page_title ?? 'Annuaire local' ?>">
    <meta property="og:description" content="<?= SITE_DESCRIPTION ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= BASE_URL ?>/public/">
    <meta property="og:image" content="<?= BASE_URL ?>/public/assets/img/og-image.jpg">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= SITE_NAME ?> | <?= $page_title ?? 'Annuaire local' ?>">
    <meta name="twitter:description" content="<?= SITE_DESCRIPTION ?>">
    <meta name="twitter:image" content="<?= BASE_URL ?>/public/assets/img/og-image.jpg">

    <title><?= SITE_NAME ?> | <?= $page_title ?? 'Annuaire local' ?></title>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="/ecoparakou/public/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/ecoparakou/public/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/ecoparakou/public/favicon_io/favicon-16x16.png">
    <link rel="shortcut icon" href="/ecoparakou/public/favicon_io/favicon.ico" type="image/x-icon">
    <link rel="manifest" href="/ecoparakou/public/favicon_io/site.webmanifest">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <!-- Global CSS -->
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/style.css">
  </head>
  <body>