<?php
include_once __DIR__ . '/constants.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= SITE_DESCRIPTION ?>">
  <meta name="author" content="<?= SITE_AUTHOR ?>">
  <title><?= SITE_NAME ?> | <?= $page_title ?? 'Annuaire local' ?></title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- FontAwesome (optionnel) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- CSS global -->
  <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/style.css">

  <!-- Couleurs du thème -->
  <style>
    :root {
      --color-primary: <?= COLOR_PRIMARY ?>;
      --color-accent: <?= COLOR_ACCENT ?>;
      --color-bg: <?= COLOR_BG ?>;
      --color-text: <?= COLOR_TEXT ?>;
    }
  </style>
</head>
<body>