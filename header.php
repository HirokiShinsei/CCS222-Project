<head>
    <link rel="stylesheet" href="header.css">
</head>
<body>
    <?php session_start() ?>
    <header>
        <h2>DiscussDen</h2>
        <div class="dropdown">
            <img src="img/home.png" alt="" class="icon">
            <p>Home</p>
            <img src="img/drop down.png" alt="" class="icon">
        </div>
        <svg class="searchbar">
            <rect x="0" y="0" rx="15" ry="15" width="100%" height="100%" stroke="none" fill="#e4e4e4"/>
            <image href="img/search.png" x="-22%" y="25%" width="50%" height="50%"/>
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
</body>