<?php
include_once '../includes/db.php';
include_once '../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $mdp = trim($_POST['mot_de_passe']);

  $stmt = $mysqli->prepare("SELECT id, nom, mot_de_passe, role FROM utilisateurs WHERE email = ? AND actif = 1");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows === 1) {
    $user = $res->fetch_assoc();
    if (password_verify($mdp, $user['mot_de_passe'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_nom'] = $user['nom'];
      $_SESSION['user_role'] = $user['role'];
      header("Location: dashboard.php");
      exit;
    }
  }
  $erreur = "Identifiants invalides.";
}
?>

<!-- Formulaire HTML -->
<?php include_once '../includes/meta-head.php'; ?>
<div class="container mt-5">
  <h2>Connexion</h2>
  <?php if (!empty($erreur)) echo "<p class='text-danger'>$erreur</p>"; ?>
  <form method="post">
    <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
    <input type="password" name="mot_de_passe" class="form-control mb-3" placeholder="Mot de passe" required>
    <button type="submit" class="btn btn-primary">Se connecter</button>
  </form>
</div>
<?php include_once '../includes/footer.php'; ?>