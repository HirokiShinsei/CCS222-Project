<?php

session_start();

$db_file = __DIR__ . '\forum_database.db';
$db = new PDO('sqlite:' . $db_file);

$stmt = $db -> prepare('UPDATE users SET username = :user WHERE username = :current_user');
$stmt -> bindParam(':current_user', $_SESSION['username']);
$stmt -> bindParam(':user', $_POST['newname']);
$stmt -> execute();

// change every comment to new username
$stmt = $db -> prepare('UPDATE comments SET name = :name WHERE name = :current_user');
$stmt -> bindParam(':current_user', $_SESSION['username']);
$stmt -> bindParam(':name', $_POST['newname']);
$stmt -> execute();

// Change session and cookie to new username
$_SESSION['username'] = $_POST['newname'];
setcookie("username", $_SESSION['username'], time() + (86400 * 7), "/");

header('Location: profile.php');

?>