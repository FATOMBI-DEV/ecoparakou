<?php
$page_title = "Ajouter un membre";
include_once '../../includes/meta-head.php';
include_once '../../includes/header.php';
include_once '../../includes/db.php';
include_once '../../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nom = trim($_POST['nom']);
  $email = trim($_POST['email']);
  $mdp = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
  $role = $_POST['role'];

  $stmt = $mysqli->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $nom, $email, $mdp, $role);
  $stmt->execute();

  header("Location: liste.php?ajout=1");
  exit;
}
?>

<div class="container mt-5">
  <h2 class="mb-4">Ajouter un membre</h2>
  <form method="post">
    <div class="mb-3">
      <label for="nom" class="form-label">Nom</label>
      <input type="text" name="nom" id="nom" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" name="email" id="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="mot_de_passe" class="form-label">Mot de passe</label>
      <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="role" class="form-label">Rôle</label>
      <select name="role" id="role" class="form-select" required>
        <option value="moderateur">Modérateur</option>
        <option value="admin">Administrateur</option>
      </select>
    </div>
    <button type="submit" class="btn btn-success">Créer le compte</button>
  </form>
</div>

<?php include_once '../../includes/footer.php'; ?>