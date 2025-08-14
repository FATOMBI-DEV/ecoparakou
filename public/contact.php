<?php
$page_title = "Contact";
include_once '../includes/meta-head.php';
include_once '../includes/header.php';
?>

<div class="container mt-5">
  <h2 class="mb-4">Contacter notre équipe</h2>
  <form action="/controllers/contact.php" method="post">
    <div class="mb-3">
      <label for="nom" class="form-label">Votre nom</label>
      <input type="text" name="nom" id="nom" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Votre email</label>
      <input type="email" name="email" id="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="sujet" class="form-label">Sujet</label>
      <input type="text" name="sujet" id="sujet" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="message" class="form-label">Message</label>
      <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Envoyer</button>
  </form>
</div>

<?php include_once '../includes/footer.php'; ?>