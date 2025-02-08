<?php
$sql = 'CREATE TABLE users (
	userID int AUTO_INCREMENT NOT NULL,
    email varchar(255) UNIQUE NOT NULL,
    passwordHash varchar(255) NOT NULL,
    confirmationCode int NOT NULL,
    isEmailConfirmed TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (userID)
);';

$sql = 'CREATE TABLE people (
	personID int AUTO_INCREMENT NOT NULL,
    firstName varchar(255) NOT NULL,
    lastName varchar(255) NOT NULL,
    OIB varchar(255) NOT NULL,
    yearOfBirth int NOT NULL,
    educationLevel varchar(255) NOT NULL,
    yearsOfExperience int NOT NULL,
    jobCategories varchar(255) NOT NULL,
    resume varchar(255) NOT NULL,
    PRIMARY KEY (personID)
);';
?>

