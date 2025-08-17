  <?php
    $page_title = "Contact";
  ?>

  <!DOCTYPE html>
  <html lang="fr">

    <head>
      <?php include_once '../includes/meta-head.php'; ?>
      <link rel="stylesheet" href="assets/css/header.css">
      <link rel="stylesheet" href="assets/css/footer.css">
      <link rel="stylesheet" href="assets/css/contact.css">
    </head>

    <body>

      <?php include_once '../includes/header.php'; ?>

      <main>
        <div class="contact-wrapper">
          <div class="container">
            <h2>Contacter notre équipe</h2>

            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
              <div class="contact-message success">
                <i class="bi bi-check-circle-fill me-2"></i>
                Votre message a été envoyé avec succès.
              </div>
            <?php elseif (isset($_GET['error']) && $_GET['error'] == 1): ?>
              <div class="contact-message error">
                <i class="bi bi-x-circle-fill me-2"></i>
                Une erreur est survenue. Veuillez réessayer.
              </div>
            <?php endif; ?>

            <form id="contactForm" action="/ecoparakou/controllers/contact1.php" method="post" novalidate>
              <label for="nom">Votre nom</label>
              <input type="text" name="nom" id="nom" required>
              <div class="field-error" id="error-nom"></div>

              <label for="email">Votre email</label>
              <input type="email" name="email" id="email" required>
              <div class="field-error" id="error-email"></div>

              <label for="sujet">Sujet</label>
              <input type="text" name="sujet" id="sujet" required>
              <div class="field-error" id="error-sujet"></div>

              <label for="message">Message</label>
              <textarea name="message" id="message" rows="5" required></textarea>
              <div class="field-error" id="error-message"></div>

              <button type="submit">Envoyer</button>
            </form>
          </div>
        </div>
      </main>

      <?php include_once '../includes/footer.php'; ?>
      <script>
        document.addEventListener('DOMContentLoaded', () => {
          const form = document.getElementById('contactForm');
          const fields = ['nom', 'email', 'sujet', 'message'];

          form.addEventListener('submit', e => {
            let valid = true;

            fields.forEach(field => {
              const input = document.getElementById(field);
              const errorDiv = document.getElementById('error-' + field);

              if (!input.value.trim()) {
                errorDiv.textContent = "Ce champ est requis.";
                errorDiv.style.display = "block";
                valid = false;
              } else {
                errorDiv.textContent = "";
                errorDiv.style.display = "none";
              }
            });

            if (!valid) {
              e.preventDefault();
            }
          });

          // Efface l’erreur dès que l’utilisateur tape
          fields.forEach(field => {
            const input = document.getElementById(field);
            const errorDiv = document.getElementById('error-' + field);

            input.addEventListener('input', () => {
              if (input.value.trim()) {
                errorDiv.textContent = "";
                errorDiv.style.display = "none";
              }
            });
          });
        });

          document.addEventListener('DOMContentLoaded', () => {
            const message = document.querySelector('.contact-message');
            if (message) {
              setTimeout(() => {
                message.style.opacity = '0';
                message.style.transform = 'translateY(-10px)';
                message.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                setTimeout(() => message.remove(), 600); // Supprime le message du DOM après transition
              }, 5000);
            }
          });
      </script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
  </html>