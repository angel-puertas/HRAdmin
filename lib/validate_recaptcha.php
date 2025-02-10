<?php
require_once __DIR__ . '/../vendor/autoload.php';
function validateRecaptcha($recaptchaResponse) {
    if(empty($recaptchaResponse)) {
        echo 'Empty reCAPTCHA token. Please try again.';
        exit;
    }

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();

    $recaptchaSecretKey = $_ENV['RECAPTCHA_SECRET_KEY'];

    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecretKey&response=$recaptchaResponse");    
    $responseKeys = json_decode($response, true);

    if (!isset($responseKeys["success"]) || $responseKeys["success"] !== true) {
        return false;
    } else {
        return true;
    }
}
?>