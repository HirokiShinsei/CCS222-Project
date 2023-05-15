<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('Location: 403-Forbidden.html');
    exit();
}

$db_file = __DIR__ . '\forum_database.db';
$db = new PDO('sqlite:' . $db_file);

$stmt = $db -> prepare('DELETE FROM posts WHERE id = :id');
$stmt -> bindParam(':id', $_POST['id']);
$stmt -> execute();

if ($stmt -> rowCount() > 0) echo "Successfully deleted row.";
else echo "No rows deleted.";
?>