<?php
/*
*******************************************************
CHANGE-PROFILE.PHP

- Changes the profile to the new submitted avatar
********************************************************
*/

// Prevent direct url attack (redirect to 403 Forbidden)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('Location: 403-Forbidden.html');
    exit();
}

// Start the session
session_start();

// Access the databse
$db_file = __DIR__ . '\forum_database.db';
$db = new PDO('sqlite:' . $db_file);

// Change the user_profile's src path to the new one
$stmt = $db -> prepare('UPDATE users SET profile_src = :profile WHERE username = :username');
$stmt -> bindParam(':profile', $_POST['newfill']);
$stmt -> bindParam(':username', $_SESSION['username']);
$stmt -> execute();

?>