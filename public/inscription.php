<?php

$page_title = "Inscription entreprise";
include_once '../includes/constants.php';
include_once '../includes/db.php';
include_once '../includes/fonctions.php';


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php include_once '../includes/meta-head.php'; ?>
  <link rel="stylesheet" href="assets/css/inscription.css">
</head>
<body>

<?php include_once '../includes/header.php'; ?>


  <div class="container mt-5">
    <h2 class="mb-4 text-primary">Inscrire votre entreprise</h2>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
  <div class="fade-message">
    <i class="bi bi-check-circle-fill text-success me-2"></i>
    Votre demande a été envoyée avec succès. Elle est en cours de validation.
  </div>
<?php endif; ?>
    <!-- Barre de progression -->
    <div class="progress mb-4">
      <div class="progress-bar" role="progressbar" id="progressBar">Étape 1 sur 3</div>
    </div>

    <form action="../controllers/inscription1.php" method="post" enctype="multipart/form-data" id="formInscription" novalidate>
      
      <!-- Étape 1 : Infos générales -->
      <div class="form-step active">
        <div class="mb-3">
          <label for="nom" class="form-label">Nom de l'entreprise</label>
          <input type="text" name="nom" id="nom" class="form-control" required>
          <div class="invalid-feedback">Veuillez entrer le nom de l'entreprise.</div>
        </div>

        <div class="mb-3">
          <label for="email_contact" class="form-label">Email</label>
          <input type="email" name="email_contact" id="email_contact" class="form-control" required>
          <div class="invalid-feedback">Veuillez entrer un email valide.</div>
        </div>

        <div class="mb-3">
          <label for="telephone" class="form-label">Téléphone</label>
          <input type="text" name="telephone" id="telephone" class="form-control" required pattern="^[0-9\s\+\-]+$">
          <div class="invalid-feedback">Veuillez entrer un numéro valide.</div>
        </div>

        <div class="mb-3">
          <?php $secteurs = $mysqli->query("SELECT id, nom FROM secteurs ORDER BY ordre"); ?>
          <label for="secteur_id" class="form-label">Secteur d'activité</label>
          <select name="secteur_id" id="secteur_id" class="form-select" required>
            <option value="">-- Choisir un secteur --</option>
            <?php while ($s = $secteurs->fetch_assoc()): ?>
              <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nom']) ?></option>
            <?php endwhile; ?>
          </select>
          <div class="invalid-feedback">Veuillez sélectionner un secteur.</div>
        </div>

        <button type="button" class="btn btn-accent next-step">Suivant</button>
      </div>

      <!-- Étape 2 : Localisation -->
      <div class="form-step">
        <div class="mb-3">
          <label for="adresse" class="form-label">Adresse</label>
          <textarea name="adresse" id="adresse" class="form-control" rows="2" required></textarea>
          <div class="invalid-feedback">Veuillez entrer une adresse.</div>
        </div>

        <div class="mb-3">
          <label for="quartier" class="form-label">Quartier</label>
          <input type="text" name="quartier" id="quartier" class="form-control" required>
          <div class="invalid-feedback">Veuillez indiquer le quartier.</div>
        </div>

        <div class="mb-3">
          <label for="localisation" class="form-label">Lien Google Maps (optionnel)</label>
          <input type="url" name="localisation" id="localisation" class="form-control">
        </div>

        <button type="button" class="btn btn-secondary prev-step">Précédent</button>
        <button type="button" class="btn btn-accent next-step">Suivant</button>
      </div>

      <!-- Étape 3 : Présentation -->
      <div class="form-step">
        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
          <div class="invalid-feedback">Veuillez fournir une description.</div>
        </div>

        <div class="mb-3">
          <label for="logo" class="form-label">Logo (max <?= MAX_LOGO_SIZE_MB ?> Mo)</label>
          <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
        </div>

        <button type="button" class="btn btn-secondary prev-step">Précédent</button>
        <button type="submit" class="btn btn-primary">Soumettre la demande</button>
      </div>

    </form>
  </div>

  <?php include_once '../includes/footer.php'; ?>

  <!-- JS Bootstrap + navigation étapes -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    setTimeout(() => {
      const msg = document.querySelector('.fade-message');
      if (msg) msg.style.display = 'none';
    }, 5000);
  document.addEventListener('DOMContentLoaded', () => {
  const steps = document.querySelectorAll('.form-step');
  const nextBtns = document.querySelectorAll('.next-step');
  const prevBtns = document.querySelectorAll('.prev-step');
  const progressBar = document.getElementById('progressBar');
  let currentStep = 0;

  function updateStepDisplay() {
    steps.forEach((step, index) => {
      step.classList.toggle('active', index === currentStep);
      if (index !== currentStep) {
        step.classList.remove('was-validated'); // Réinitialiser validation visible sur étapes non-actives
      }
    });
    const pourcentage = ((currentStep + 1) / steps.length) * 100;
    progressBar.style.width = pourcentage + '%';
    progressBar.textContent = `Étape ${currentStep + 1} sur ${steps.length}`;

    // Focus automatique sur 1er champ de l’étape active (bonne UX)
    const firstInput = steps[currentStep].querySelector('input, select, textarea');
    if (firstInput) {
      firstInput.focus();
    }
  }

  nextBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const currentForm = steps[currentStep];
      const inputs = currentForm.querySelectorAll('input, select, textarea');
      let allValid = true;

      // Force le déclenchement de la validation native pour chaque champ
      inputs.forEach(input => {
        if (!input.checkValidity()) {
          allValid = false;
        }
      });

      if (!allValid) {
        // Affiche les messages d’erreur bootstrap
        currentForm.classList.add('was-validated');
        return; 
      }

      // on passe à l’étape suivante
      if (currentStep < steps.length - 1) {
        currentStep++;
        updateStepDisplay();
      }
    });
  });

  prevBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      if (currentStep > 0) {
        currentStep--;
        updateStepDisplay();
      }
    });
  });

  // Validation finale lors de la soumission
  document.getElementById('formInscription').addEventListener('submit', e => {
    
    const form = e.target;
    if (!form.checkValidity()) {
      e.preventDefault();
      // marquer toutes les étapes invalides : 
      steps.forEach(step => {
        step.classList.add('was-validated');
      });
     
    }
  });

  updateStepDisplay();
});

  </script>
</body>
</html>