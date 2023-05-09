<?php 
    $db_file = __DIR__ . '\forum_database.db';
    $db = new PDO('sqlite:' . $db_file);

    // Get current date and time
    $date = new DateTime("now", new DateTimeZone("Asia/Manila"));

    $stmt = $db -> prepare('INSERT INTO comments (post_id, name, content, date) VALUES (:post_id, :name, :content, :date)');
    $stmt -> bindParam(':post_id', $_POST['post_id'], PDO::PARAM_INT);
    $stmt -> bindParam(':name', $_POST['username'], PDO::PARAM_STR);
    $stmt -> bindParam(':content', $_POST['comment'], PDO::PARAM_STR);
    $stmt -> bindParam(':date', $date -> format('F j, Y g:i A'));
    $stmt -> execute();


?>