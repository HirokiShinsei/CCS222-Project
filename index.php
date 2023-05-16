<?php
/*
*******************************************************
INDEX.PHP

- The main page of the website
- Redirects to home page if session exists;
otherwise, redirects to login.php
********************************************************
*/

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum Website</title>
    <link rel="icon" href="img/icon.png">
</head>
<body>
    <?php 
    session_set_cookie_params(86400);
    session_start();
    
    // If session expired, redirect to login page
    if (isset($_SESSION['expire_time']) && time() > $_SESSION['expire_time']) {
        session_unset();
        session_destroy();

        header('Location: login.php?session=expired');
        exit();
    }

    if(isset($_SESSION['username'])) {
        header('Location: home');
    } else {
        header('Location: login');
    }?>
    
</body>
</html>