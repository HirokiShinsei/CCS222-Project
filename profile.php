<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="icon" href="img/icon.png">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="post-boxes.css">
    
</head>
<body>
    <section class="post-section">
    <?php 
        include "header.php";
         // Insert all posts here
        $stmt = $db->prepare('SELECT * FROM posts WHERE user_id = :userID');
        $stmt->bindParam(':userID', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Iterate through every post
        foreach (array_reverse($posts) as $post) {

            // Get post user details
            $stmt = $db -> prepare('SELECT * FROM users WHERE user_id = :userID');
            $stmt -> bindParam(':userID', $post['user_id'], PDO::PARAM_INT);
            $stmt -> execute();
            $post_user = $stmt -> fetch(PDO::FETCH_ASSOC);

            // Create post container and content
            echo    '<div class="post-container">
                        <div class="post-user">
                            <svg width=35 height=35>
                                <circle cx=17.5 cy=17.5 r=17.5 fill=black/>
                            </svg>
                            <h4 class="username">' . $post_user['username'] . '</h4>
                            <p>' . $post['date'] . '</p>
                        </div>
                        <div class="post-content">
                            <h2>' . $post['title'] . '</h2>
                            <p>' . $post['content'] . '</p>';

            if (isset($_SESSION['username'])) {
                echo '<button class="like" data-id="' . $post['id'] . '">';
                
                // Like count
                if (($likes = json_decode($post['likes'])) !== NULL) {
                    echo '<p class="likes">' . count(json_decode($post['likes'])) . '</p>';
                }

                    // Like or dislike
                    echo '<img class="like-state" ';
                        if(!((array_search($_SESSION['username'], json_decode($post['likes']))) === false)) {
                            echo 'src="img/upvote-filled.png"';
                        } else {
                            echo 'src="img/upvote-nofill.png"';
                        }
                    echo '/>
                </button>';
            } else {
                echo '<button disabled>Like</button>';
            }

            echo '</div><hr>';
            
            // Insert all comments here
            $stmt = $db -> prepare('SELECT * FROM comments WHERE post_id = :post_id');
            $stmt -> bindParam(':post_id', $post['id'], PDO::PARAM_INT);
            $stmt -> execute();
            $comments = $stmt -> fetchAll(PDO::FETCH_ASSOC);

            // Iterate through all comments related to post
            foreach ($comments as $comment) {
                echo    '<div class="comment-container">
                            <div>
                                <svg width=30 height=30>
                                    <circle cx=15 cy=15 r=15 fill=black/>
                                </svg>
                                <h4 class="username">' . $comment['name'] . '</h4>
                                <p>(' . $comment['date'] . ')</p>
                            </div>
                            <p>' . $comment['content'] . '</p>
                        </div>';
            }

            echo '
                    <form method="post" action="add_comment.php" class="comment-box">
                        <input type="hidden" name="post_id" value="' . $post['id'] . '">
                ';

            if (isset($_SESSION['username'])) { 
                echo '
                        <input type="hidden" name="username" value="' . $_SESSION['username'] . '"> 
                        <div>
                            <svg width=35 height=35>
                                <circle cx=17.5 cy=17.5 r=17.5 fill=black/>
                            </svg>
                            <h4 class="username">' . $_SESSION['username'] . '</h4>
                        </div>
                        <textarea name="comment" placeholder="Comment"></textarea>
                        <input type="submit" value="Comment">
                        ';
            } else { 
                echo '
                        <input type="hidden" name="username" value="Anonymous">
            '; }
            echo '</form></div>';
        }

    ?>
    </section>
</body>
</html>