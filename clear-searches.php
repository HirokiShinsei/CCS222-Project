<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();

    $db_file = __DIR__ . '\forum_database.db';
    $db = new PDO('sqlite:' . $db_file);

    $stmt = $db -> prepare('UPDATE users SET recent_searches = NULL WHERE username = :username');
    $stmt -> bindParam(':username', $_SESSION['username']);
    $stmt -> execute();
}
?>