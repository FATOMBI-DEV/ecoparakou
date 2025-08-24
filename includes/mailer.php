    <?php
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        require_once __DIR__ . '/../libs/phpmailer/src/PHPMailer.php';
        require_once __DIR__ . '/../libs/phpmailer/src/SMTP.php';
        require_once __DIR__ . '/../libs/phpmailer/src/Exception.php';

        function envoyer_notification($destinataire, $sujet, $contenu_html) {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'parakoueco@gmail.com';
                $mail->Password = 'cjaufapxsqvgixao';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom(EMAIL_ADMIN, SITE_NAME);
                $mail->addAddress($destinataire);
                $mail->isHTML(true);
                $mail->Subject = $sujet;
                $mail->Body = $contenu_html;

                // Encodage UTF-8
                $mail->CharSet = 'UTF-8';
                $mail->Encoding = 'base64';

                $mail->send();
                return true;
            }
            catch (Exception $e) {
                error_log("Erreur PHPMailer : " . $mail->ErrorInfo);
                return false;
            }
        }