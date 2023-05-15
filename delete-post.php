<?php
if (empty($_SERVER['HTTP_REFERER']) || !strpos($_SERVER['HTTP_REFERER'], 'localhost/CCS222-Project')) {
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