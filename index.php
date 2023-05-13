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
    
    if(isset($_SESSION['username'])) {
        header('Location: home.php');
    } else {
        header('Location: login.php');
    }?>
    
</body>
</html>