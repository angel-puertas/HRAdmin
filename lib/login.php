<?php
require_once('db.php');
require_once __DIR__ . '/../vendor/autoload.php';
session_start();

if(isset($_POST['login'])) {
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

    $stmt = $userDB->prepare("SELECT userID, email, passwordHash, isEmailConfirmed FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0) {
        $stmt->bind_result($userID, $email, $passwordHash, $isEmailConfirmed);
        $stmt->fetch();

        if(!$isEmailConfirmed) {
            die("Email address unconfirmed.");
        }

        if(password_verify($password, $passwordHash)) {
            $_SESSION['user_id'] = $userID;
            $_SESSION['email'] = $email;

            exit("Login successful");
        } else {
            die("Invalid password.");
        }
    } else {
        die("No user found with that email address.");
    }   
}
?>