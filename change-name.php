<?php
/*
*******************************************************
CHANGE_NAME.PHP

- Changes user name
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

// get the new name from the submitted data
$newname = strip_tags($_POST['newname']);

// Check if username is unique
$stmt = $db -> prepare('SELECT * FROM users WHERE username = :new_user');
$stmt -> bindParam(':new_user', $newname);
$stmt -> execute();

$names = $stmt -> fetchAll(PDO::FETCH_ASSOC);

if (count($names) > 0) {
    header('Location: profile.php?error=user_exists');
    exit;
}

// set the new username in users
$stmt = $db -> prepare('UPDATE users SET username = :user WHERE username = :current_user');
$stmt -> bindParam(':current_user', $_SESSION['username']);
$stmt -> bindParam(':user', $newname);
$stmt -> execute();

// change every comment to new username
$stmt = $db -> prepare('UPDATE comments SET name = :name WHERE name = :current_user');
$stmt -> bindParam(':current_user', $_SESSION['username']);
$stmt -> bindParam(':name', $newname);
$stmt -> execute();

// Change session and cookie to new username
$_SESSION['username'] = $newname;
setcookie("username", $_SESSION['username'], time() + (86400 * 7), "/");

// redirect to profile page
header('Location: profile');

?>