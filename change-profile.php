<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('Location: 403-Forbidden.html');
    exit();
}

session_start();

$db_file = __DIR__ . '\forum_database.db';
$db = new PDO('sqlite:' . $db_file);

$stmt = $db -> prepare('UPDATE users SET profile_src = :profile WHERE username = :username');
$stmt -> bindParam(':profile', $_POST['newfill']);
$stmt -> bindParam(':username', $_SESSION['username']);
$stmt -> execute();


?>