<?php
if (empty($_SERVER['HTTP_REFERER']) || !strpos($_SERVER['HTTP_REFERER'], 'localhost/CCS222-Project')) {
    header('Location: 403-Forbidden.html');
    exit();
}

session_start();

$db_file = __DIR__ . '\forum_database.db';
$db = new PDO('sqlite:' . $db_file);

$newname = strip_tags($_POST['newname']);

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

header('Location: profile.php');

?>