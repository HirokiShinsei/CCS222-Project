<?php 
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
        header('Location: 403-Forbidden.html');
        exit();
    }

    $db_file = __DIR__ . '\forum_database.db';
    $db = new PDO('sqlite:' . $db_file);

    // Get current date and time
    $date = new DateTime("now", new DateTimeZone("Asia/Manila"));

    $stmt = $db -> prepare('INSERT INTO comments (post_id, name, content, date) VALUES (:post_id, :name, :content, :date)');
    $stmt -> bindParam(':post_id', $_POST['post_id'], PDO::PARAM_INT);
    $stmt -> bindParam(':name', $_POST['username'], PDO::PARAM_STR);
    $stmt -> bindParam(':content', strip_tags(nl2br($_POST['comment']), ['<br>', '<br/>']), PDO::PARAM_STR);
    $stmt -> bindParam(':date', $date -> format('F j, Y g:i A'));
    $stmt -> execute();


?>