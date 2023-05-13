<header>
    <?php 

    // generate session cookie
    session_set_cookie_params(86400);
    session_start(); 

    // access database
    $db_file = __DIR__ . '\forum_database.db';

    // create new PDO from database
    $db = new PDO('sqlite:' . $db_file);

    // Get recent searches
    if (isset($_SESSION['username'])) {
        $stmt = $db -> prepare('SELECT recent_searches FROM users WHERE username = :username');
        $stmt -> bindParam(':username', $_SESSION['username']);
        $stmt -> execute();

        $recent_searches = $stmt -> fetch(PDO::FETCH_ASSOC)['recent_searches'];
    }

    // Set recent searches
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
        if (isset($recent_searches)) {
            $searches = json_decode($recent_searches, true);
            
            if (!(($index = array_search($_POST['search'], $searches)) === false)) {
                unset($searches[$index]);
            }
            array_unshift($searches, $_POST['search']);
            
            $searches = array_slice($searches, 0, 5);
        } else {
            $searches = array($_POST['search']);
            
        }
        $searches = json_encode($searches);
        
        // Update recent search in database
        $stmt = $db -> prepare('UPDATE users SET recent_searches = :recent_searches WHERE username = :username');
        $stmt -> bindParam(':username', $_SESSION['username']);
        $stmt -> bindParam(':recent_searches', $searches);
        $stmt -> execute();

        header('refresh:0');
    }

    // Place recent searches into variable $searches
    if (isset($recent_searches))
        $searches = json_decode($recent_searches, true);

    // Get user profile from session
    if (isset($_SESSION['username'])) {
        $stmt = $db -> prepare('SELECT * FROM users WHERE username = :user');
        $stmt -> bindParam(':user', $_SESSION['username']);
        $stmt -> execute();

        $user_profile = $stmt -> fetch(PDO::FETCH_ASSOC)['profile_src'];
    }
    
    ?> 

    <!-- Title -->
    <h2>DiscussDen</h2>

    <!-- Search bar -->
    <form action="" method="post" id="searchbar">
        <!-- Search box -->
        <div id="search-box">
            <!-- This is where the typed suggestion is placed -->
            <section id="user-typed"></section> 

            <!-- Recent searches -->
            <?php if (isset($searches)) echo '<p>RECENT SEARCHES</p>' ?>
            <div id="recent-searches">
                <?php
                    if (isset($searches)) {
                        
                        foreach($searches as $search) {
                                echo '<section>' . $search . '</section>';
                        }
                    }
                ?>
            </div>

            <!-- Search suggestions -->
            <p id="search-suggest-label"></p>
            <div id="search-suggestions"></div>
        </div>
        
        <img src="img/search.png" alt="" class="icon">
        <input name="search" type="search" placeholder="Search a post or article" autocomplete="off" tabindex=0>
    </form>

    <!-- User Button -->
    <?php if(isset($_SESSION['username'])) {
        echo '
        <div id="user-btn" tabindex=0>
            <svg width=50 height=50>
                <defs>
                    <clipPath id="avatar-clip" clipPathUnits="objectBoundingBox" >
                        <circle cx=0.5 cy=0.5 r=0.5 />
                    </clipPath>
                </defs>

                <image href="' . $user_profile . '" width=100% height=100% clip-path="url(#avatar-clip)" />
            </svg>
            <p class="username">' . $_SESSION['username'] . '</p>
        </div>
        ';
    } else {
        echo '<a href="login.php" tabindex=3>Log In or Sign Up</a>';
    } ?>

    <script src="header.js"></script>
</header>