<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a post</title>
    <link rel="icon" href="img/icon.png">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="createpost.css">
    
</head>
<body>
    <?php 
        include_once "header.php";
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            session_start();

            // Open database file
            $db_file = __DIR__ . '\forum_database.db';
            $db = new PDO('sqlite:' . $db_file);

            // Get current logged in user id from logged in username
            $stmt = $db -> prepare('SELECT * FROM users WHERE username = :username');
            $stmt -> bindParam(':username', $_SESSION['username']);
            $stmt -> execute();
            $_SESSION['user_id'] = $stmt -> fetch(PDO::FETCH_ASSOC)['user_id'];

            // Get current date and time
            $date = new DateTime("now", new DateTimeZone("Asia/Manila"));

            // Insert post data into a new row (in table posts)
            $stmt = $db -> prepare('INSERT INTO posts (user_id, title, content, date, likes) VALUES (:userID, :title, :content, :date, :likes)');
            $stmt -> bindParam(':userID', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt -> bindParam(':title', strip_tags($_POST['title']), PDO::PARAM_STR);
            $stmt -> bindParam(':content', strip_tags(nl2br($_POST['content']), ['<br>', '<br/>']), PDO::PARAM_STR);
            $stmt -> bindParam(':date', $date -> format('F j, Y g:i A'));
            $stmt -> bindParam(':likes', json_encode(array()));
            $stmt -> execute();

            // Redirect to home.php
            header('Location: home.php');
            exit;
        }
    ?>
    <div class="post">
        <h1 class="post-head">Create a post</h1>
        <!-- <hr class="post"> -->
        <form action="create-a-post.php" method="post" class="form" autocomplete="off">
            <input type="text" name="title" class="title" placeholder="Title..." required>
            <textarea name="content" class="content" placeholder="Write something..." required></textarea>
            <input type="submit" value="Post">
        </form>
    </div>
    <script>
        document.querySelector('.content').addEventListener('input', () => {
            document.querySelector('.content').style.height = 'auto';
            document.querySelector('.content').style.height = document.querySelector('.content').scrollHeight + 'px';
        });
    </script>
</body>
</html>