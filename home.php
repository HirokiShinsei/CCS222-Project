<?php
    function generateLikeText($users_who_liked, $current_user_id = null) {
        $total_likes = count($users_who_liked);
        $you_liked = false;
        $usernames = [];
    
        foreach ($users_who_liked as $user) {
            if ($user['user_id'] == $current_user_id) {
                $you_liked = true;
            } else {
                $usernames[] = $user['username'];
            }
        }
    
        if ($total_likes == 0) {
            return "";
        }
    
        $text = "";
    
        if ($you_liked) {
            $text .= "You";
            if ($total_likes > 1) {
                $text .= " and " . ($total_likes - 1) . " others";
            }
        } else {
            if ($total_likes == 1) {
                $text .= $usernames[0];
            } else if ($total_likes == 2) {
                $text .= $usernames[0] . " and " . $usernames[1];
            } else {
                $text .= $usernames[0] . " and " . ($total_likes - 1) . " others";
            }
        }
    
        $text .= " liked this post";
    
        return $text;
    }
?>
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
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include "header.php"?>

    <div class="post-tab">
        <svg width=35 height=35>
            <circle cx=17.5 cy=17.5 r=17.5 fill=black/>
        </svg>
        <input type="text" placeholder="Create a post" onclick="window.location.href='create-a-post.php'">
        <img src="img/image.png" alt="" class="icon">
    </div>

    <!-- /* best-hot-new */ -->
    <div class="post-tab">

    <div class="group">
        <a href="#" class="reddit-button">
            <img src="img/hot.png" alt="" class="icon"/>
            <span class="txt-hot"><span>Hot</span></span>
        </a>
    </div>

    <div class="group">
        <a href="#" class="reddit-button">
            <img src="img/new.png" alt="" class="icon" />
            <span class="new"><span>New</span></span>
        </a>
    </div>

    <div class="group">
        <a href="#" class="reddit-button">
            <img src="img/top.png" alt="" class="icon" />
            <span class="top"><span>Top</span></span>
        </a>
    </div>

    </div>

    <section class="post-section">
    <?php
        $db_file = __DIR__ . '\forum_database.db';
        $db = new PDO('sqlite:' . $db_file);

        // Insert all posts here
        $stmt = $db -> prepare('SELECT * FROM posts');
        $stmt -> execute();
        $posts = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        
        // Iterate through every post
        foreach ($posts as $post) {

            // Count the number of likes
            $stmt = $db->prepare('SELECT COUNT(*) as likes FROM post_likes WHERE post_id = :post_id AND is_like = 1');
            $stmt->bindParam(':post_id', $post['id'], PDO::PARAM_INT);
            $stmt->execute();
            $likes = $stmt->fetch(PDO::FETCH_ASSOC);
            
            //Fetches who liked the post/s
            $stmt = $db->prepare('SELECT users.user_id, users.username FROM post_likes INNER JOIN users ON post_likes.user_id = users.user_id WHERE post_likes.post_id = :post_id AND post_likes.is_like = 1');
            $stmt->bindParam(':post_id', $post['id'], PDO::PARAM_INT);
            $stmt->execute();
            $users_who_liked = $stmt->fetchAll(PDO::FETCH_ASSOC);

             // Check if the current user already liked the post
            $user_already_liked = false;
            if (isset($_SESSION['user_id'])) {
                $stmt = $db->prepare('SELECT * FROM post_likes WHERE post_id = :post_id AND user_id = :user_id AND is_like = 1');
                $stmt->bindParam(':post_id', $post['id'], PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
                $stmt->execute();
                $user_like = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($user_like) {
                    $user_already_liked = true;
                }
            }

            

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
                            <p>' . $post['content'] . '</p>
                        </div>
                        <div class="likes-count"' . ($likes['likes'] == 0 ? ' style="display:none;"' : '') . '>' . $likes['likes'] . '</div>
                        <form method="post" action="like-posts.php" class="like-form">
                            <input type="hidden" name="post_id" value="' . $post['id'] . '">
                            <input type="hidden" name="user_id" value="' . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '') . '">
                            <input type="hidden" name="is_like" value="' . ($user_already_liked ? '0' : '1') . '">
                            <button type="submit" class="like-button">' . ($user_already_liked ? 'Unlike' : 'Like') . '</button>
                            <div class="like-text">' . ($likes['likes'] > 0 ? generateLikeText($users_who_liked, isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null) : '') . '</div>
                        </form>
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
    <script>
        document.querySelectorAll('form.comment-box textarea').forEach(textarea => {
            textarea.addEventListener('input', () => {
                textarea.style.height = '1rem';
                textarea.style.height = textarea.scrollHeight + 'px';
            });
        })
    </script>
    <script>
    $(document).ready(function() {
        $(".like-form").submit(function(e) {
            e.preventDefault();
            const form = $(this);
            const likeButton = form.find(".like-button");
            const likesCount = form.prev(".likes-count");

            $.ajax({
                url: "like-posts.php",
                type: "post",
                data: form.serialize(),
                success: function(response) {
                    if (likeButton.text() === "Like") {
                        likeButton.text("Unlike");
                        form.find("input[name='is_like']").val("0");
                        likesCount.text(parseInt(likesCount.text()) + 1);
                    } else {
                        likeButton.text("Like");
                        form.find("input[name='is_like']").val("1");
                        likesCount.text(parseInt(likesCount.text()) - 1);
                    }
                },
            });
        });
    });
</script>
</body>
</html>