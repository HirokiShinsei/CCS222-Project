<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="tabs.css">
    
</head>
<body>
    <?php include "header.php" ?>

    <?php
        $db_file = __DIR__ . '\forum_database.db';
        $db = new PDO('sqlite:' . $db_file);

        // Insert all posts here
        $stmt = $db -> prepare('SELECT * FROM posts');
        $stmt -> execute();
        $posts = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        // Iterate through every post
        foreach ($posts as $post) {
            echo '<div> <h2>' . $post['title'] . '</h2>';
            echo '<p>' . $post['content'] . '</p>';
            
            // Insert all comments here
            $stmt = $db -> prepare('SELECT * FROM comments WHERE post_id = :post_id');
            $stmt -> bindParam(':post_id', $post['id'], PDO::PARAM_INT);
            $stmt -> execute();
            $comments = $stmt -> fetchAll(PDO::FETCH_ASSOC);

            // Iterate through all comments related to post
            foreach ($comments as $comment) {
                echo '<p><strong>' . $comment['name'] . '</strong> replied:</p>';
                echo '<p>' . $comment['content'] . '</p></div>';
            }

            echo '
            <form method="post" action="add_comment.php">
                <input type="hidden" name="post_id" value="' . $post['id'] . '">
                ';

            if (isset($_SESSION['username'])) { echo '<input type="hidden" name="username" value="' . $_SESSION['username'] . '"> 
                <svg width=35 height=35>
                    <circle cx=17.5 cy=17.5 r=17.5 fill=black/>
                </svg>
                <p class="username">' . $_SESSION['username'] . '</p>
                <textarea name="comment" placeholder="Comment"></textarea>
                <input type="submit" value="Comment">';
            }
            else { echo '<input type="hidden" name="username" value="Anonymous">'; }
            echo '</form>';
        }


    ?>
</body>
</html>