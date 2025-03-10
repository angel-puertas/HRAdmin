<?php
// HTTPS redirection
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

require_once('db.php');
require_once('validate_recaptcha.php');
require_once __DIR__ . '/../vendor/autoload.php';
session_start();

if(isset($_POST['login'])) {
    if (!validateRecaptcha($_POST['g-recaptcha-response'])) {
        die("reCAPTCHA validation failed");
    }

    $email = $_POST['email'];
    $password = $_POST['password'];
    $isAdmin = $_POST['is_admin'];

    $stmt = $userDB->prepare("SELECT userID, email, passwordHash, isEmailConfirmed, isAdmin FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0) {
        $stmt->bind_result($userID, $email, $passwordHash, $isEmailConfirmed, $isAdmin);
        $stmt->fetch();

        if(!$isEmailConfirmed) {
            die("Email address unconfirmed.");
        }

        if(password_verify($password, $passwordHash)) {
            $_SESSION['user_id'] = $userID;
            $_SESSION['email'] = $email;
            $_SESSION['isAdmin'] = $isAdmin;

            exit("Login successful");
        } else {
            die("Invalid password.");
        }
    } else {
        die("No user found with that email address.");
    }   
}
?>