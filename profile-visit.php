<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="icon" href="img/icon.png">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="post-boxes.css">
    
</head>
<body>
    <?php 
        include "header.php";

        if (empty($_GET['user_id']))
            header('Location: 404.html');
        // Get linked user's name from link ID
        $stmt = $db -> prepare('SELECT * FROM users WHERE user_id = :user');
        $stmt -> bindParam(':user', $_GET['user_id']);
        $stmt -> execute();

        $user_name = $stmt -> fetch(PDO::FETCH_ASSOC)['username'];
    ?>
    <section class="post-section" style="margin-top:3rem">
        <div class="post-container">
            <svg width=75 height=75>
                <circle cx=50% cy=50% r=50% fill=black/>
            </svg>
            <h2 class="username"><?php echo $user_name ?></h2>
            <p>Where the fun starts. Everyday.</p>
        </div>

        <?php


        // Insert all posts here
        $stmt = $db -> prepare('SELECT * FROM posts WHERE user_id = :user');
        $stmt -> bindParam(':user', $_GET['user_id']);
        $stmt -> execute();
        $posts = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        
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

                // Get user id of commenter
                $stmt = $db -> prepare('SELECT * FROM users WHERE username = :user');
                $stmt -> bindParam(':user', $comment['name'], PDO::PARAM_STR);
                $stmt -> execute();

                $user_id = $stmt -> fetch(PDO::FETCH_ASSOC)['user_id'];

                echo    '<div class="comment-container">
                            <div>
                                <svg width=30 height=30>
                                    <circle cx=50% cy=50% r=50% fill=black/>
                                </svg>';

                                if ($post['user_id'] !== $user_id){
                                    if ($_SESSION['username'] === $comment['name']) echo '<a href="profile.php">';
                                    else echo '<a href="profile-visit.php?user_id=' . $user_id . '">';
                                } 
                                echo '<h4 class="username">' . $comment['name'] . '</h4>';
                                
                                if ($post['user_id'] !== $user_id) echo '</a>';

                                echo '<p>(' . $comment['date'] . ')</p>
                            </div>
                            <p>' . $comment['content'] . '</p>
                        </div>';
            }

            echo '
                    <form class="comment-box">
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
    <script src="post-boxes.js"></script>
</body>
</html>