    <?php

    require_once 'constants.php';
    require_once 'mailer.php';
    function generer_slug(string $texte): string {
        $texte = trim($texte);
        $texte = strtolower($texte);
        $texte = iconv('UTF-8', 'ASCII//TRANSLIT', $texte); // Supprime les accents
        $texte = preg_replace('/[^a-z0-9\s-]/', '', $texte); // Supprime caractères spéciaux
        $texte = preg_replace('/[\s-]+/', '-', $texte); // Remplace espaces/tirets multiples
        $texte = trim($texte, '-');

        // Fallback si slug vide
        if (empty($texte)) {
            return strtolower(uniqid());
        }

        return $texte;
    }



    function envoyer_email($to, $sujet, $message) {
        $headers = "From: " . EMAIL_ADMIN . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        return mail($to, $sujet, $message, $headers);
    }

    function enregistrer_action($utilisateur_id, $action, $table, $cible_id) {
        global $mysqli;
        $stmt = $mysqli->prepare("INSERT INTO logs_actions (utilisateur_id, action, table_cible, cible_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $utilisateur_id, $action, $table, $cible_id);
        $stmt->execute();
    }

    //Nouvelle fonction modulaire de log
    function log_action($utilisateur_id, $action, $table_cible, $cible_id = null) {
    global $mysqli;
    $action = htmlspecialchars($action);
    $table_cible = htmlspecialchars($table_cible);
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'inconnue';
    $stmt = $mysqli->prepare("INSERT INTO logs_actions (utilisateur_id, action, table_cible, cible_id, date_action)
        VALUES (?, ?, ?, ?, NOW())
    ");
    $stmt->bind_param("issi", $utilisateur_id, $action, $table_cible, $cible_id);
    $stmt->execute();
    $stmt->close();
    }
    function notifierAction($type, $contenu, $lien = '', $emailCible = null) {
        global $mysqli;

        // Notification interne à tous les admins actifs
        $admins = $mysqli->query("SELECT id, email FROM utilisateurs WHERE actif = 1 AND role = 'admin'");
        while ($admin = $admins->fetch_assoc()) {
            $stmt = $mysqli->prepare("INSERT INTO notifications (utilisateur_id, type, contenu, lien) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $admin['id'], $type, $contenu, $lien);
            $stmt->execute();
        }

        // Email 
        if ($emailCible) {
            $subject = "Notification - $type";
            $body = "<p>$contenu</p><p><a href='$lien'>Voir</a></p>";
            $headers = "From: " . EMAIL_ADMIN . "\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            mail($emailCible, $subject, $body, $headers);
        }
    }