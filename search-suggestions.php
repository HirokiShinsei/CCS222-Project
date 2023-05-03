<?php

$db_file = __DIR__ . '\forum_database.db';
$db = new PDO('sqlite:' . $db_file);

$stmt = $db->prepare("SELECT title FROM posts WHERE title LIKE :term LIMIT 10");
$stmt->bindValue(':term', '%' . $_POST['searchTerm'] . '%');
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($results);
?>