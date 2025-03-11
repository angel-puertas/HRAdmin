<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <title>HR Administration</title>
    <link rel="icon" type="image/x-icon" href="images/logo.ico">
    <link rel='stylesheet' href='css/style.css?v=1.0'>
    <script src='javascript/modals.js?v=1.0'></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    <div class='grid-container'>
        <div class='logo'>
            <img src='images/logo.png' alt='logo'>
            <?php if(!empty($_SESSION['user_id'])) { ?>
                <div>Logged in as: <?=$_SESSION['email']?></div>
            <?php } ?>
        </div>
        <div class='menu'>
            <ul>
                <li><a href='index.php'>Home</a></li>
                <li><a href='personInformation.php'>People</a></li>
                <li><a href='categoryAdministration.php'>Categories</a></li>
                <?php if(!empty($_SESSION) && $_SESSION['isAdmin'] == 1) { ?>
                    <li><a href='admin.php'>Admin</a></li>
                <?php } ?>
                <?php if(empty($_SESSION['user_id'])) { ?>
                    <li><a id='login-link' onclick='openModal("login")'>Log In</a></li>
                <?php } else { ?>
                    <li><a href='lib/logout.php'>Log Out</a></li>
                <?php } ?>
            </ul>
        </div>