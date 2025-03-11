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

    $stmt = $userDB->prepare("SELECT userID, email, passwordHash, isEmailConfirmed, isAdmin, isLocked, failedLoginCount FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0) {
        $stmt->bind_result($userID, $email, $passwordHash, $isEmailConfirmed, $isAdmin, $isLocked, $failedLoginCount);
        $stmt->fetch();

        if(!$isEmailConfirmed) {
            die("Email address unconfirmed.");
        }

        if($isLocked) {
            die("Account is locked.");
        }

        if(password_verify($password, $passwordHash)) {
            $_SESSION['userID'] = $userID;
            $_SESSION['email'] = $email;
            $_SESSION['isAdmin'] = $isAdmin;

            // Reset failedLoginCount
            $stmt = $userDB->prepare("UPDATE users SET failedLoginCount = 0 WHERE userID = ?");
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $stmt->close();

            $sql = "SELECT settingValue FROM settings WHERE settingKey = 'rememberMeDaysToExpiration'";
            $result = $userDB->query($sql);
            $row = $result->fetch_assoc();
            $rememberMeDaysToExpiration = (int) $row['settingValue'];

            // Create Remember Me Cookie
            if(isset($_POST['rememberMe']) && $_POST['rememberMe'] == '1') {
                $token = bin2hex(random_bytes(16));
                setcookie('rememberMe', $token, time() + (86400 * $rememberMeDaysToExpiration), "/"); // 30 days
                
                $stmt = $userDB->prepare("UPDATE users SET rememberMe = ? WHERE userID = ?");
                $stmt->bind_param("si", $token, $userID);
                $stmt->execute();
                $stmt->close();
            }

            exit("Login successful");
        } else {
            $sql = "SELECT settingValue FROM settings WHERE settingKey = 'maxFailedLogins'";
            $result = $userDB->query($sql);
            $row = $result->fetch_assoc();
            $maxFailedLogins = (int) $row['settingValue'];

            $newFailedCount = $failedLoginCount + 1;

            $stmt = $userDB->prepare("UPDATE users SET failedLoginCount = 0 WHERE userID = ?");
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $stmt->close();

            if ($newFailedCount >= $maxFailedLogins) {
                $stmt = $userDB->prepare("UPDATE users SET isLocked = 1 WHERE userID = ?");
                $stmt->bind_param("i", $userID);
                $stmt->execute();
                $stmt->close();

                die("Too many failed logins. Account has been locked.");
            }

            die("Invalid password.");
        }
    } else {
        die("No user found with that email address.");
    }   
}
?>