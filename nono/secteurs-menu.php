<?php
include_once 'db.php';
$secteurs = $mysqli->query("SELECT nom, slug FROM secteurs ORDER BY ordre");

while ($s = $secteurs->fetch_assoc()) {
    echo '<li><a class="dropdown-item" href="/secteur.php?slug=' . $s['slug'] . '">' . htmlspecialchars($s['nom']) . '</a></li>';
}