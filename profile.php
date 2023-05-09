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
    ?>
    <section class="post-section" style="margin-top:3rem">
        <div class="post-container">
            <svg width=75 height=75>
                <circle cx=50% cy=50% r=50% fill=black/>
            </svg>
            <h2 class="username"><?php echo $_SESSION['username'] ?></h2>
            <p>Where the fun starts. Everyday.</p>
        </div>

        <?php
        // Get current user's ID
        $stmt = $db -> prepare('SELECT * FROM users WHERE username = :user');
        $stmt -> bindParam(':user', $_SESSION['username']);
        $stmt -> execute();

        $user_id = $stmt -> fetch(PDO::FETCH_ASSOC)['user_id'];

        // Insert all posts here
        $stmt = $db -> prepare('SELECT * FROM posts WHERE user_id = :user');
        $stmt -> bindParam(':user', $user_id);
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

            echo '
                        <svg width=25 height=25 class="delete-btn">
                            <circle cx=50% cy=50% r=45% stroke=black stroke-width=10% fill="transparent"/>
                            <line x1=35% y1=35% x2=65% y2=65% stroke=black stroke-width=15% stroke-linecap="round" />
                            <line x1=35% y1=65% x2=65% y2=35% stroke=black stroke-width=15% stroke-linecap="round" />
                            </svg>
                            <div class="delete-confirm">
                            <h3 class="username">Are you sure you want to delete this post?</h3>
                            <button onclick="delete_post(' . $post['id'] . ')">Yes</button>
                            <button onclick="this.parentElement.classList.remove(\'active\')">No</button>
                        </div>

                        <svg width=25 height=25 class="edit-btn" data-id="' . $post['id'] . '">
                            <circle cx=50% cy=50% r=45% stroke=black stroke-width=10% fill=transparent />
                            <g transform="rotate(45 12.5 12.5)">
                                <rect x=42.5% y=22% width=15% height=50% ry=7% fill=black stroke=none />
                                <path d="M 10.5 17 L 12.5 20 L 14.5 17 Z"/> 
                            </g>
                        </svg>
                    </div><hr>';
            
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
                                    <circle cx=50% cy=50% r=50% fill=black/>
                                </svg>
                                <h4 class="username">' . $comment['name'] . '</h4>
                                <p>(' . $comment['date'] . ')</p>
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