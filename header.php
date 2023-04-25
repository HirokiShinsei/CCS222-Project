<?php 
session_start();

function setDropdowns() {
    if ($_SERVER['PHP_SELF'] != '/CCS222-Project/home.php') {
        echo 
        '
        <div class="dropdown-item" onclick="window.location.href = \'home.php\';">
            <img src="img/home.png" alt="" class="icon">
            <p>Home</p>
        </div>
        ';
    }
    if ($_SERVER['PHP_SELF'] != '/CCS222-Project/communities.php') {
        echo 
        '
        <div class="dropdown-item" onclick="window.location.href = \'communities.php\';">
            <img src="img/add.png" alt="" class="icon">
            <p>Communities</p>
        </div>
        ';
    }
    if ($_SERVER['PHP_SELF'] != '/CCS222-Project/trending.php') {
        echo 
        '
        <div class="dropdown-item" onclick="window.location.href = \'trending.php\';">
            <img src="img/trend.png" alt="" class="icon">
            <p>Trending</p>
        </div>
        ';
    }
}

function setMainTab() {
    switch($_SERVER['PHP_SELF']) {
        case '/CCS222-Project/home.php':
            echo '<img src="img/home.png" alt="" class="icon"><p>Home</p>';
            break;
        case '/CCS222-Project/communities.php':
            echo '<img src="img/add.png" alt="" class="icon"><p>Communities</p>';
            break;
        case '/CCS222-Project/trending.php':
            echo '<img src="img/trend.png" alt="" class="icon"><p>Trending</p>';
            break;
    }
}
?>


<header id="main-header">
    <h2>DiscussDen</h2>

    <!-- All Tabs -->
    <div id="dropdown">

        <!-- Set Main Tab -->
        <?php setMainTab() ?>

        <!-- Dropdown arrow -->
        <img src="img/drop down.png" alt="dropdownicon" class="icon">

        <!-- Dropdown box -->
        <nav class="dropdown-box">
            <section></section>

            <!-- Dropdown options -->
            <?php setDropdowns() ?>
        </nav>
    </div>

    <!-- Search bar -->
    <svg id="searchbar">
        <rect x="0" y="0" rx="15" ry="15" width="100%" height="100%" stroke="none" fill="#e4e4e4"/>
        <image href="img/search.png" x="-22%" y="25%" width="50%" height="50%"/>
        <foreignObject x="7%" y="0" width=85% height=100%>
            <form action="" method="post" id="searchtext">
                <input type="text" name="searchquery" placeholder="Search a post or article">
            </form>
        </foreignObject>
    </svg>
    
    <!-- User Icon -->
    <?php if (isset($_SESSION['username'])) {
    echo 
    '
    <div class="user">
        <svg width="40" height="40">
            <circle cx="20" cy="20" r="20" fill="black" />
        </svg>
        <p id="firstname">' . $_SESSION['firstname'] . '</p>
        <p id="username">' . $_SESSION['username'] . '</p>
    </div>
    ';
    } else {
        echo
        '<a href="login.php" id="redirect">Log In or Sign Up</a>';
    }
    ?>
</header>
<script src="header.js"></script>