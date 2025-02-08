<?php
require_once('db.php');
session_start();

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $userDB->prepare("SELECT userID, email, passwordHash FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0) {
        $stmt->bind_result($userID, $email, $hashedPassword);
        $stmt->fetch();

        if(password_verify($password, $hashedPassword)) {
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