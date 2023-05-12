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
        include_once "header.php";

        // Get current user's ID
        $stmt = $db -> prepare('SELECT * FROM users WHERE username = :user');
        $stmt -> bindParam(':user', $_SESSION['username']);
        $stmt -> execute();

        $user = $stmt -> fetch(PDO::FETCH_ASSOC);
        $user_id = $user['user_id'];
        $user_description = $user['description'];

    ?>
    <section class="post-section" style="margin-top:3rem">
        
        <div class="post-container">
            <svg width=75 height=75>
                <defs>
                    <clipPath id="circle-clip">
                        <circle cx=50% cy=50% r=50% clip-path="url(#triangle-clip)" />
                    </clipPath>

                    <clipPath id="triangle-clip">
                        <path d="M 75 35 L 75 75 L 35 75" />
                    </clipPath>
                    
                    <clipPath id="avatar-clip" clipPathUnits="objectBoundingBox" >
                        <circle cx=0.5 cy=0.5 r=0.5 />
                    </clipPath>
                </defs>
                
                <image href="<?php echo $user['profile_src'] ?>" width=100% height=100% clip-path="url(#avatar-clip)" />

                <foreignObject x=0 y=0 width=100% height=100% clip-path="url(#circle-clip)" >
                    <!-- <div ></div> -->
                    <button style="width:100%;height:100%;background-color:#0005;" id="modify_profile"></button>
                    </foreignObject>
                    <g transform="rotate(45 12.5 12.5) translate(66 0)" style="cursor:pointer">
                        <rect x=10.625 y=5.5 width=3.75 height=12.5 ry=1.75 fill=white stroke=none />
                        <path d="M 10.5 15 L 12.5 20 L 14.5 15 Z" fill=white /> 
                    </g>
            </svg>

            <h2 class="username"><?php echo $_SESSION['username'] ?></h2>
            <p><?php echo $user_description?></p>

            <svg width=5 height=25 id="option-btn" tabindex=1>
                <circle cx=50% cy=25% r=12% fill=black />
                <circle cx=50% cy=50% r=12% fill=black />
                <circle cx=50% cy=75% r=12% fill=black />
            </svg>
            <div class="modal option" tabindex=0>
                <div class="tab-option" tabindex=-1>
                    <p>Change Name</p>
                </div>
                <div class="tab-option" tabindex=-1>
                    <p>Change Description</p>
                </div>
            </div>
        </div>

        <div id="backdrop"></div>
        
        <form action="change-name.php" method="post" id="change-name">
            <svg width=25 height=25 class="exit-btn">
                <circle cx=50% cy=50% r=45% stroke=black stroke-width=10% fill="transparent"/>
                <line x1=35% y1=35% x2=65% y2=65% stroke=black stroke-width=15% stroke-linecap="round" />
                <line x1=35% y1=65% x2=65% y2=35% stroke=black stroke-width=15% stroke-linecap="round" />
            </svg>
            <h2 class="username">Change Profile Username</h2>
            <input type="text" placeholder="Enter new username here" name="newname">
            <input type="submit" value="Change Username">
        </form>
        <form action="change-description.php" method="post" id="change-description">
            <svg width=25 height=25 class="exit-btn">
                <circle cx=50% cy=50% r=45% stroke=black stroke-width=10% fill="transparent"/>
                <line x1=35% y1=35% x2=65% y2=65% stroke=black stroke-width=15% stroke-linecap="round" />
                <line x1=35% y1=65% x2=65% y2=35% stroke=black stroke-width=15% stroke-linecap="round" />
            </svg>
            <h2 class="username">Change Profile Bio</h2>
            <input type="text" placeholder="Enter new bio here" name="newdescription">
            <input type="submit" value="Change Bio">
        </form>
            
        <div id="profile-upload-popup">
            <svg width=25 height=25 class="exit-btn">
                <circle cx=50% cy=50% r=45% stroke=black stroke-width=10% fill="transparent"/>
                <line x1=35% y1=35% x2=65% y2=65% stroke=black stroke-width=15% stroke-linecap="round" />
                <line x1=35% y1=65% x2=65% y2=35% stroke=black stroke-width=15% stroke-linecap="round" />
            </svg>
            <h2 class="username">Select Profile Avatar</h2>
            <div id="profile-avatars">
                <?php
                $avatars = glob("avatars/*.png");
                foreach($avatars as $avatar) {
                    echo '<svg width=30 height=30>
                            <clipPath id="avatars-clip">
                                <circle cx=50% cy=50% r=50% />
                            </clipPath>
                            <image href="' . $avatar . '" width=100% height=100% clip-path="url(#avatars-clip)" class="profile-option" />
                        </svg>';
                }
                ?>
            </div>
        </div>


        <?php
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
                                <image href="' . $post_user['profile_src'] . '" x=0 y=0 width=35 height=35 clip-path="url(#avatar-clip)" />
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

                        <svg width=25 height=25 class="edit-btn" data-id="' . $post['id'] . '">
                            <circle cx=50% cy=50% r=45% stroke=black stroke-width=10% fill=transparent />
                            <g transform="rotate(45 12.5 12.5)">
                                <rect x=42.5% y=22% width=15% height=50% ry=7% fill=black stroke=none />
                                <path d="M 10.5 17 L 12.5 20 L 14.5 17 Z"/> 
                            </g>
                        </svg>
                        <div class="delete-confirm">
                            <h3 class="username">Are you sure you want to delete this post?</h3>
                            <div>
                                <button class="yes-btn" onclick="delete_post(' . $post['id'] . ')">Yes</button>
                                <button class="no-btn" onclick="this.parentElement.parentElement.classList.remove(\'active\')">No</button>
                            </div>
                        </div>
                    </div><hr>';
            
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
                                </svg>';
                                
                                if ($post['user_id'] !== $user_id) echo '<a href="profile-visit.php?user_id=' . $user_id . '">';
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
                                <image href="' . $post_user['profile_src'] . '" width=100% height=100% clip-path="url(#avatar-clip)" />
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