<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <link rel="icon" href="img/icon.png">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="post-boxes.css">
</head>
<body>
    <?php include_once "header.php" ?>
    
    <section class="post-section">
    <h1 id="search-results">Search Results</h1>

    <?php 
    
    // Check if search is set and not empty
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) {
        $searchTerm = $_GET['search'];

        // Get post searches
        $stmt = $db -> prepare("SELECT * FROM posts WHERE title LIKE :term OR content LIKE :term");
        $stmt->bindValue(':term', '%' . $searchTerm . '%');
        $stmt->execute();
        $results = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = $db -> prepare("SELECT * FROM users WHERE username LIKE :term");
        $stmt->bindValue(':term', '%' . $searchTerm . '%');
        $stmt->execute();
        $users = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        // Iterate through every user 
        foreach($users as $user) {
            // Create post user
            echo '<div class="post-container" style="cursor:pointer" onclick="window.location.href=\'';
            if (isset($_SESSION['username']) && $_SESSION['username'] === $user['username']) echo 'profile.php';
            else echo 'profile-visit.php?user_id=' . $user['user_id'];
            echo '\'">
                <div class="post-user">
                    <svg width=35 height=35>
                        <image href="' . $user['profile_src'] . '" x=0 y=0 width=35 height=35 clip-path="url(#avatar-clip)" />
                    </svg>
                    <h4 class="username">' . $user['username'] . '</h4>
                    <p>Click to view Profile</p>
                </div>
            </div>';
        }
        
        // Iterate through every post
        foreach ($results as $result) {

            if (isset($result['title'])) {
                // Get post user details
                $stmt = $db -> prepare('SELECT * FROM users WHERE user_id = :userID');
                $stmt -> bindParam(':userID', $result['user_id'], PDO::PARAM_INT);
                $stmt -> execute();
                $result_user = $stmt -> fetch(PDO::FETCH_ASSOC);

                // Create post container and content
                echo    '<div class="post-container">
                            <div class="post-user">
                                <svg width=35 height=35>
                                    <image href="' . $result_user['profile_src'] . '" x=0 y=0 width=35 height=35 clip-path="url(#avatar-clip)" />
                                </svg>
                                <a href="';
                                if (isset($_SESSION['username']) && $_SESSION['username'] === $result_user['username']) echo 'profile.php';
                                else echo 'profile-visit.php?user_id=' . $result['user_id'];
                                echo '"><h4 class="username">' . $result_user['username'] . '</h4></a>
                                <p>' . $result['date'] . '</p>
                            </div>
                            <div class="post-content">
                                <h2>' . $result['title'] . '</h2>
                                <p>' . $result['content'] . '</p>';

                if (isset($_SESSION['username'])) {
                    echo '<button class="like" data-id="' . $result['id'] . '">';
                    
                    // Like count
                    if (($likes = json_decode($result['likes'])) !== NULL) {
                        echo '<p class="likes">' . count(json_decode($result['likes'])) . '</p>';
                    }

                        // Upvoted or not
                        echo '<img class="like-state" ';
                            if(!((array_search($_SESSION['username'], json_decode($result['likes']))) === false)) {
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
                $stmt -> bindParam(':post_id', $result['id'], PDO::PARAM_INT);
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
                            <input type="hidden" name="post_id" value="' . $result['id'] . '">
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
        }
    }

    ?>
    </section>
    <script src="post-boxes.js"></script>
</body>
</html>
