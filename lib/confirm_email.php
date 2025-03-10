<?php
require_once('db.php');

if(isset($_POST['email-confirmation'])) {
    $email = $_POST['email'];
    $inputtedCode = (int) $_POST['confirmation-code'];
    $isEmailConfirmed = 1;

    $stmt = $userDB->prepare("SELECT confirmationCode FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($storedCode);
    $stmt->fetch();
    $stmt->close();
    
    if ($inputtedCode === $storedCode) {
        $stmt = $userDB->prepare("UPDATE users SET isEmailConfirmed = ? WHERE email = ?");
        $stmt->bind_param("is", $isEmailConfirmed, $email);
        $stmt->execute();
        $stmt->close();
    
        $userDB->close();
        exit('Email confirmation successful');
    } else {
        die('Wrong confirmation code');
    }
} else {
    die('No email confirmation data received');
}
?>