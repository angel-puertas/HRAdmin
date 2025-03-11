<?php
session_start();
require_once("db.php");

// Delete Cookie from client
if(isset($_COOKIE['rememberMe'])) {
    setcookie('rememberMe', '', time() - 3600, "/");
}

// Delete Cookie from server
$userID = $_SESSION['userID'];
$stmt = $userDB->prepare("UPDATE users SET rememberMe = NULL WHERE userID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->close();

session_unset();
session_destroy();
header("Location: ../index.php");
exit();
?>