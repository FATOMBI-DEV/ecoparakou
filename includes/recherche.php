<?php
include_once 'db.php';
$q = trim($_GET['q'] ?? '');

if ($q !== '') {
    $stmt = $mysqli->prepare("SELECT id, nom, slug FROM entreprises WHERE statut = 'valide' AND nom LIKE CONCAT('%', ?, '%') LIMIT 10");
    $stmt->bind_param("s", $q);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo '<div><a href="/entreprise.php?slug=' . $row['slug'] . '">' . htmlspecialchars($row['nom']) . '</a></div>';
    }
}