<?php
/*
*******************************************************
DELETE-POST.PHP

- Deletes the post with the submitted post id
********************************************************
*/

// Prevents direct url attack (redirects to 403 Forbidden)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('Location: 403-Forbidden.html');
    exit();
}

// Access the database
$db_file = __DIR__ . '\forum_database.db';
$db = new PDO('sqlite:' . $db_file);

// Delete the post with the submitted post id
$stmt = $db -> prepare('DELETE FROM posts WHERE id = :id');
$stmt -> bindParam(':id', $_POST['id']);
$stmt -> execute();

// Prints the success statement
if ($stmt -> rowCount() > 0) echo "Successfully deleted row.";
else echo "No rows deleted.";
?>