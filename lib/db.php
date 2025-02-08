<?php
$host = 'localhost';
$username = 'root';
$password = '';

$personDB = @new MySQLi($host, $username, $password, 'personDB');

if ($personDB->connect_errno) {
    print "Error: " . $personDB->connect_error;
    exit();
}

$categoryDB = @new MySQLi($host, $username, $password, 'categoryDB');

if ($categoryDB->connect_errno) {
    print "Error: " . $categoryDB->connect_error;
    exit();
}

$userDB = @new MySQLi($host, $username, $password, 'userDB');

if ($userDB->connect_errno) {
    print "Error: " . $userDB->connect_error;
    exit();
}
?>