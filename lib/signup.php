<?php
require_once('db.php');
require_once('validate_recaptcha.php');
require_once __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['signup'])) {
    if (!validateRecaptcha($_POST['g-recaptcha-response'])) {
        die("reCAPTCHA validation failed");
    }

    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeatPassword = $_POST['repeat-password'];

    // SERVER SIDE FORM VALIDATION
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
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