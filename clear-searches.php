<?php
/*
*******************************************************
CLEAR_SEARCHES.PHP

- Clears search history
********************************************************
*/

// Prevents direct url attack (redirect to 403 Forbidden)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('Location: 403-Forbidden.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Start the session
    session_start();

    // Access the database
    $db_file = __DIR__ . '\forum_database.db';
    $db = new PDO('sqlite:' . $db_file);

    // Delete the search history of user
    $stmt = $db -> prepare('UPDATE users SET recent_searches = NULL WHERE username = :username');
    $stmt -> bindParam(':username', $_SESSION['username']);
    $stmt -> execute();
}
?>