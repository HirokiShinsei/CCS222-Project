<?php

session_start();

$db_file = __DIR__ . '\forum_database.db';
$db = new PDO('sqlite:' . $db_file);

$stmt = $db -> prepare('UPDATE users SET profile_src = :profile WHERE username = :username');
$stmt -> bindParam(':profile', $_POST['newfill']);
$stmt -> bindParam(':username', $_SESSION['username']);
$stmt -> execute();


?>