<header>
    <?php session_start(); ?>

    <h2>DiscussDen</h2>

    <!-- Tab Box -->
    <div id="tab-box">
        <?php 
            if ($_SERVER['PHP_SELF'] == '/CCS222-Project/home.php') {
                echo '
                <div class="tab-option">
                    <img src="img/home.png" alt="" class="icon">
                    <p>Home</p>
                </div>
                <div class="tab-option" onclick="window.location.href=\'communities.php\'">
                    <img src="img/add.png" alt="" class="icon">
                    <p>Communities</p>
                </div>
                <div class="tab-option" onclick="window.location.href=\'trending.php\'">
                    <img src="img/trend.png" alt="" class="icon">
                    <p>Trending</p>
                </div>
                ';
            }
            else if ($_SERVER['PHP_SELF'] == '/CCS222-Project/communities.php') {
                echo '
                <div class="tab-option">
                    <img src="img/add.png" alt="" class="icon">
                    <p>Communities</p>
                </div>
                <div class="tab-option" onclick="window.location.href=\'home.php\'">
                    <img src="img/home.png" alt="" class="icon">
                    <p>Home</p>
                </div>
                <div class="tab-option" onclick="window.location.href=\'trending.php\'">
                    <img src="img/trend.png" alt="" class="icon">
                    <p>Trending</p>
                </div>
                ';
            }
            else if ($_SERVER['PHP_SELF'] == '/CCS222-Project/trending.php') {
                echo '
                <div class="tab-option">
                    <img src="img/trend.png" alt="" class="icon">
                    <p>Trending</p>
                </div>
                <div class="tab-option" onclick="window.location.href=\'home.php\'">
                    <img src="img/home.png" alt="" class="icon">
                    <p>Home</p>
                </div>
                <div class="tab-option" onclick="window.location.href=\'communities.php\'">
                    <img src="img/add.png" alt="" class="icon">
                    <p>Communities</p>
                </div>
                ';
            }
        ?>

        <img src="img/drop down.png" alt="" class="dropdown icon">
    </div>

    <!-- Search bar -->
    <form action="" method="get" id="searchbar">
        <img src="img/search.png" alt="" class="icon">
        <input name="search" type="text" placeholder="Search a post or article" autocomplete="off">
    </form>

    <!-- User Button -->
    <?php if(isset($_SESSION['username'])) {
        echo '
        <div id="user-btn">
            <svg width=50 height=50>
                <circle cx=25 cy=25 r=25 fill=black />
            </svg>
            <p class="firstname">' . $_SESSION['firstname'] . '</p>
            <p class="username">' . $_SESSION['username'] . '</p>

            <div class="tab-option" name="logout">
                <img src="img/add.png" alt="" class="icon">
                <p>Log out</p>
            </div>
        </div>
        ';
    } else {
        echo '<a href="login.php">Log In or Sign Up</a>';
    } ?>

    <script src="header.js"></script>
</header>