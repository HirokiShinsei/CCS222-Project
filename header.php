<head>
    <link rel="stylesheet" href="header.css">
</head>
<body>
    <?php 
    session_start();
    
    function setDropdowns() {
        if ($_SERVER['PHP_SELF'] != '/ccs-final/home.php') {
            echo 
            '
            <div class="dropdown-item" onclick="window.location.href = \'home.php\';">
                <img src="img/home.png" alt="" class="icon">
                <p>Home</p>
            </div>
            ';
        }
        if ($_SERVER['PHP_SELF'] != '/ccs-final/communities.php') {
            echo 
            '
            <div class="dropdown-item" onclick="window.location.href = \'communities.php\';">
                <img src="img/add.png" alt="" class="icon">
                <p>Communities</p>
            </div>
            ';
        }
        if ($_SERVER['PHP_SELF'] != '/ccs-final/trending.php') {
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
            case '/ccs-final/home.php':
                echo '<img src="img/home.png" alt="" class="icon"><p>Home</p>';
                break;
            case '/ccs-final/communities.php':
                echo '<img src="img/add.png" alt="" class="icon"><p>Communities</p>';
                break;
            case '/ccs-final/trending.php':
                echo '<img src="img/trend.png" alt="" class="icon"><p>Trending</p>';
                break;
        }
    }
    ?>
    <header>
        <h2>DiscussDen</h2>
        <div class="dropdown">
            <?php setMainTab() ?>
            <img src="img/drop down.png" alt="dropdownicon" class="icon">
            <nav class="dropdown-box">
                <section></section>
                <?php setDropdowns() ?>
            </nav>
        </div>
        <svg class="searchbar">
            <rect x="0" y="0" rx="15" ry="15" width="100%" height="100%" stroke="none" fill="#e4e4e4"/>
            <image href="img/search.png" x="-22%" y="25%" width="50%" height="50%"/>
            <foreignObject x="7%" y="0" width=85% height=100%>
                <form action="" method="post" id="searchtext">
                    <input type="text" name="searchquery" placeholder="Search a post or article">
                </form>
            </foreignObject>
        </svg>
        <?php if (isset($_SESSION['username'])) {
        echo 
        '
        <div class="user">
            <svg width="40" height="40">
                <circle cx="20" cy="20" r="20" fill="black" />
            </svg>
            <p id="firstname" username="' . $_SESSION['username'] . '">' . $_SESSION['firstname'] . '</p>
        </div>
        ';
        } else {
            echo
            '<a href="login.php" class="redirect">Log In or Sign Up</a>';
        }
        ?>
    </header>

    <script src="header.js"></script>
</body>