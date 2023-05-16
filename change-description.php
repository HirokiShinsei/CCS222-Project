<?php
/*
*******************************************************
CHANGE_DESCRIPTION.PHP

- Changes user bio
********************************************************
*/

// Prevent direct url attack (redirect to 403 Forbidden)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('Location: 403-Forbidden.html');
    exit();
}

// start the session
session_start();

// access the database
$db_file = __DIR__ . '\forum_database.db';
$db = new PDO('sqlite:' . $db_file);

// get the submitted bio as $newdescription
$newdescription = strip_tags($_POST['newdescription']);

// prepare and execute the update description statement
$stmt = $db -> prepare('UPDATE users SET description = :description WHERE username = :current_user');
$stmt -> bindParam(':current_user', $_SESSION['username']);
$stmt -> bindParam(':description', $newdescription);
$stmt -> execute();

// redirect to profile page
header('Location: profile');

?>