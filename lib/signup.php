<?php
require_once('db.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['signup'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeatPassword = $_POST['repeat-password'];

    // FORM VALIDATION
    if (!str_contains($email, "@") || !str_contains($email, ".")) {
        die("Email must contain @ and .");
    }

    if (strlen($password) < 8) {
        die("Password must be at least 8 characters long.");
    }

    if ($password !== $repeatPassword) {
        die("Wrong password.");
    }

    $stmt = $userDB->prepare("SELECT userID FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        die("An account with this email already exists");
    }
    $stmt->close();

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // CONFIRMATION EMAIL
    require '../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();

    $mail = new PHPMailer(true); // Enable exceptions
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

        // DB ENTRY
        $stmt = $userDB->prepare("INSERT INTO users (email, passwordHash, confirmationCode) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $email, $hashedPassword, $confirmationCode);
        $stmt->execute();
        $stmt->close();
        $userDB->close();

        exit('Email sent successfully');
    } catch (Exception $e) {
        exit('Email couldn\'t be sent' . $e);
    }
} else {
    die("No signup data received");
}
?>