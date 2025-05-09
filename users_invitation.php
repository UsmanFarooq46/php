<?php
// session_start();
if (session_status() === PHP_SESSION_NONE) session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/PHPMailer.php';
require '../phpmailer/Exception.php';
require '../phpmailer/SMTP.php';

function sendInvitation($pdo, $email, $role, $uCustom, $invited_by, $creator_type) {

    $token = bin2hex(random_bytes(16)); // Generate a unique token

    try {
        // Insert the invitation into the database
        $stmt = $pdo->prepare("INSERT INTO property_invitations (email, token, role, role_type, invited_by, creator_type) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$email, $token, $role, $uCustom, $invited_by, $creator_type])) {
            // Send the email
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'adnanzahidalvi675@gmail.com';
                $mail->Password = 'uazl fvbg epfm tuoy';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom($email, 'invisible');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Invitation to Register';
                // $mail->Body    = "Please click the link to complete you`r registration: <a href='https://yourdomain.com/register.php?token=$token'>Register</a>";
                $mail->Body    = "Please click the link to complete you`r registration: <a href='https://invisibletest.myagecam.net/invisible_main/html/invisible/register.php?token=$token'>Register</a>";

                $mail->send();
                return $token;
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            return false;
        }
    } catch (PDOException $e) {
        return false;
        // echo "Database error: " . $e->getMessage();
    }
}

