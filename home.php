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
    <?php include "header.php"?>
    <div class="input-section">

        <?php if (isset($_SESSION['username'])) {
            echo '
            <div class="post-tab">
                <svg width=35 height=35>
                    <image href="' . $user_profile . '" width=100% height=100% clip-path="url(#avatar-clip)" />
                </svg>
                <input type="text" placeholder="Create a post" onclick="redirect_to_post()" id="post-link">
                <img src="img/image.png" alt="" class="icon">
            </div>';
        }
        ?>

        <!-- /* best-hot-new */ -->
        <div class="post-tab">
            <div class="group <?php if(empty($_SESSION['sortMethod']) || $_SESSION['sortMethod'] == 'new') echo 'active'?>" id="sort-by-new">
                <img src="img/new.png" alt="" class="icon" />
                <span>New</span>
            </div>

            <div class="group <?php if(isset($_SESSION['sortMethod']) && $_SESSION['sortMethod'] == 'hot') echo 'active'?>" id="sort-by-hot">
                <img src="img/hot.png" alt="" class="icon"/>
                <span>Hot</span>
            </div>

            <div class="group <?php if(isset($_SESSION['sortMethod']) && $_SESSION['sortMethod'] == 'top') echo 'active'?>" id="sort-by-top">
                <img src="img/top.png" alt="" class="icon" />
                <span>Top</span>
            </div>
        </div>
    </div>

    <section class="post-section">
    <?php
        // Insert all posts here
        $stmt = $db -> prepare('SELECT * FROM posts');
        $stmt -> execute();
        $posts = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        
        if (isset($_SESSION['sortMethod']))
        {
            if ($_SESSION['sortMethod'] == 'hot') {
                usort($posts, function($a, $b) {
                    $date = new DateTime("now", new DateTimeZone("Asia/Manila"));
                    $date -> modify('-2 days');

                    if (DateTime::createFromFormat('F j, Y g:i A', $a['date']) >= $date && DateTime::createFromFormat('F j, Y g:i A', $b['date']) >= $date) {

                        if (count(json_decode($b['likes'])) == count(json_decode($a['likes'])))
                            return strtotime($b['date']) - strtotime($a['date']);
                        return count(json_decode($b['likes'])) - count(json_decode($a['likes']));
                    }

                    return strtotime($b['date']) - strtotime($a['date']);
                }); 
            }
            else if ($_SESSION['sortMethod'] == 'top') {
                usort($posts, function($a, $b) {
                    if (count(json_decode($b['likes'])) == count(json_decode($a['likes'])))
                        return strtotime($b['date']) - strtotime($a['date']);
                    return count(json_decode($b['likes'])) - count(json_decode($a['likes']));
                }); 
            }
            else {
                usort($posts, function($a, $b) {
                    return strtotime($b['date']) - strtotime($a['date']);
                }); 
            }
        } else {
            usort($posts, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            }); 
        }


        // Iterate through every post
        foreach ($posts as $post) {

            // Get post user details
            $stmt = $db -> prepare('SELECT * FROM users WHERE user_id = :userID');
            $stmt -> bindParam(':userID', $post['user_id'], PDO::PARAM_INT);
            $stmt -> execute();
            $post_user = $stmt -> fetch(PDO::FETCH_ASSOC);

            // Create post container and content
            echo    '<div class="post-container">
                        <div class="post-user">
                            <svg width=35 height=35>
                                <image href="' . $post_user['profile_src'] . '" x=0 y=0 width=35 height=35 clip-path="url(#avatar-clip)" />
                            </svg>
                            <a href="';
                            if (isset($_SESSION['username']) && $_SESSION['username'] === $post_user['username']) echo 'profile.php';
                            else echo 'profile-visit.php?user_id=' . $post['user_id'];
                            echo '"><h4 class="username">' . $post_user['username'] . '</h4></a>
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

                $comment_user = $stmt -> fetch(PDO::FETCH_ASSOC);
                $user_id = $comment_user['user_id'];

                echo    '<div class="comment-container">
                            <div>
                                <svg width=30 height=30>
                                    <image href="' . $comment_user['profile_src'] . '" width=100% height=100% clip-path="url(#avatar-clip)" />
                                </svg>
                                <a href="';
                                if (isset($_SESSION['username']) && $_SESSION['username'] === $comment['name']) echo 'profile.php';
                                else echo 'profile-visit.php?user_id=' . $user_id;
                                echo '"><h4 class="username">' . $comment['name'] . '</h4></a>
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
                                <image href="' . $user_profile . '" width=100% height=100% clip-path="url(#avatar-clip)" />
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