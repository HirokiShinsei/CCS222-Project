<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a post</title>
    <link rel="icon" href="img/icon.png">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="tabs.css">
    
</head>
<body>
    <?php 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db_file = __DIR__ . '\forum_database.db';
            $db = new PDO('sqlite:' . $db_file);

            $stmt = $db -> prepare('INSERT INTO posts (title, content) VALUES (:title, :content)');
            $stmt -> bindParam(':title', $_POST['title'], PDO::PARAM_STR);
            $stmt -> bindParam(':content', $_POST['content'], PDO::PARAM_STR);
            $stmt -> execute();

            header('location: home.php');
            exit;
        }
    ?>
    <?php include "header.php" ?>
    <h1 class="post">Create a post</h1>
    <hr class="post">
    <form action="create-a-post.php" method="post" class="post">
        <input type="text" name="title" class="title" placeholder="Title..." required>
        <textarea name="content" class="content" placeholder="Write something..." required></textarea>
        <input type="submit" value="Post">
    </form>
</body>
</html>