<?php
require_once('db.php');
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$mail = new PHPMailer(true); // Enable exceptions
$email = $_POST['email'];
$mailUsername = $_ENV['MAIL_USERNAME'];
$mailPassword = $_ENV['MAIL_PASSWORD'];
$confirmationCode = rand(100000, 999999);

try {
    // SMTP settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $mailUsername;
    $mail->Password = $mailPassword;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Email content
    $mail->setFrom($mailUsername, 'HRAdmin');
    $mail->addAddress($email, 'User');
    $mail->Subject = 'Registration Code';
    $mail->Body = 'Your code: ' . $confirmationCode;

    $mail->send();


    $stmt = $userDB->prepare("UPDATE users SET confirmationCode = ? WHERE email = ?");
    $stmt->bind_param("is", $confirmationCode, $email);
    $stmt->execute();
    $stmt->close();
    $userDB->close();

    exit('Email sent successfully');
} catch (Exception $e) {
    exit('Email couldn\'t be sent' . $e);
}
?>