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
    <?php if(isset($_COOKIE['username'])) {
        session_start();

        $_SESSION['username'] = $_COOKIE['username'];
        $_SESSION['user-link'] = $_COOKIE['user-link'];

        header('Location: home.php');
    } else {
        header('Location: login.php');
    }?>
    
</body>
</html>