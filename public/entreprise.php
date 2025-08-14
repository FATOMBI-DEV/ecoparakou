<?php
$page_title = "Fiche entreprise";
include_once '../includes/db.php';
include_once '../includes/header.php';

$slug = trim($_GET['slug'] ?? '');

if ($slug === '') {
  echo "<main class='container mt-5'><p>Entreprise introuvable.</p></main>";
  include_once '../includes/footer.php';
  exit;
}

$stmt = $mysqli->prepare("SELECT e.*, s.nom AS secteur_nom 
  FROM entreprises e 
  LEFT JOIN secteurs s ON e.secteur_id = s.id 
  WHERE e.slug = ? AND e.statut = 'valide'
");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo "<main class='container mt-5'><p>Entreprise non trouvée ou non validée.</p></main>";
  include_once '../includes/footer.php';
  exit;
}

$e = $result->fetch_assoc();
$logo = !empty($e['logo']) ? "/uploads/{$e['logo']}" : "/ecoparakou/public/assets/img/logopardefaut.png";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <?php include_once '../includes/meta-head.php'; ?>
  
  <link rel="stylesheet" href="assets/css/footer.css">
  <style>
    :root {
      --color-primary: #1F2A44;
      --color-accent: #FF9800;
      --color-bg: #F5F1EB;
      --color-text: #333333;
      --color-white: #ffffff;
      --color-muted: #6c757d;
      --font-main: 'Poppins', sans-serif;
    }

    body {
      font-family: var(--font-main);
      background-color: var(--color-bg);
      color: var(--color-text);
      padding-top: 70px;
    }

    .entreprise-logo {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid var(--color-primary);
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      margin-bottom: 1rem;
      transition: transform 0.3s ease;
    }

    .entreprise-logo:hover {
      transform: scale(1.05);
    }

    .entreprise-info-card {
      background-color: var(--color-white);
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      padding: 1.5rem;
      animation: fadeInUp 0.6s ease forwards;
    }

    .entreprise-info-card h2 {
      color: var(--color-primary);
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .entreprise-info-card p {
      font-size: 0.95rem;
      color: var(--color-text);
    }

    .info-item {
      margin-bottom: 0.75rem;
      font-size: 0.9rem;
    }

    .info-item i {
      color: var(--color-accent);
      margin-right: 0.5rem;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>
<body>

<main class="container mt-5">
  <div class="row g-4">
    <div class="col-md-4 text-center">
      <img src="<?= $logo ?>" alt="<?= htmlspecialchars($e['nom']) ?>" class="entreprise-logo">
      <div class="entreprise-info-card">
        <div class="info-item"><i class="bi bi-geo-alt-fill"></i><strong>Quartier :</strong> <?= htmlspecialchars($e['quartier']) ?></div>
        <div class="info-item"><i class="bi bi-telephone-fill"></i><strong>Téléphone :</strong> <?= htmlspecialchars($e['telephone']) ?></div>
        <div class="info-item"><i class="bi bi-envelope-fill"></i><strong>Email :</strong> <?= htmlspecialchars($e['email_contact']) ?></div>
        <div class="info-item"><i class="bi bi-house-door-fill"></i><strong>Adresse :</strong> <?= htmlspecialchars($e['adresse']) ?></div>
        <div class="info-item"><i class="bi bi-diagram-3-fill"></i><strong>Secteur :</strong> <?= htmlspecialchars($e['secteur_nom']) ?></div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="entreprise-info-card">
        <h2><?= htmlspecialchars($e['nom']) ?></h2>
        <p><?= nl2br(htmlspecialchars($e['description'])) ?></p>
      </div>
    </div>
  </div>
</main>

<?php include_once '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>