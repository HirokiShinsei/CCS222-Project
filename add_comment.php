<?php 
$db_file = __DIR__ . '\forum_database.db';
$db = new PDO('sqlite:' . $db_file);

$stmt = $db -> prepare('INSERT INTO comments (post_id, name, content) VALUES (:post_id, :name, :content)');
$stmt -> bindParam(':post_id', $_POST['post_id'], PDO::PARAM_INT);
$stmt -> bindParam(':name', $_POST['username'], PDO::PARAM_STR);
$stmt -> bindParam(':content', $_POST['comment'], PDO::PARAM_STR);
$stmt -> execute();

header('Location: home.php');

?>