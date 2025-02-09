<?php
require_once('db.php');
require_once __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['signup'])) {
    // Server side reCAPTCHA validation
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();

    $recaptchaSecretKey = $_ENV['RECAPTCHA_SECRET_KEY'];
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    if(empty($recaptchaResponse)) {
        echo 'Empty reCAPTCHA token. Please try again.';
        exit;
    }

    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecretKey&response=$recaptchaResponse");    
    $responseKeys = json_decode($response, true);

    if (!isset($responseKeys["success"]) || $responseKeys["success"] !== true) {
        echo 'reCAPTCHA verification failed. Please try again.';
        exit;
    }


    
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

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // DB ENTRY
    $stmt = $userDB->prepare("INSERT INTO users (email, passwordHash) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $passwordHash);
    $stmt->execute();
    $stmt->close();
    $userDB->close();

    exit("Signup successful");
} else {
    die("No signup data received");
}
?>