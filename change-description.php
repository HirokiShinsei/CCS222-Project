<?php

session_start();

$db_file = __DIR__ . '\forum_database.db';
$db = new PDO('sqlite:' . $db_file);

$stmt = $db -> prepare('UPDATE users SET description = :description WHERE username = :current_user');
$stmt -> bindParam(':current_user', $_SESSION['username']);
$stmt -> bindParam(':description', $_POST['newdescription']);
$stmt -> execute();

$_SESSION['username'] = $_POST['newname'];
setcookie("username", $_SESSION['username'], time() + (86400 * 7), "/");

header('Location: profile.php');

?>