<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DiscussDen Login</title>
    <link rel="icon" href="img/icon.png">
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <?php 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            session_set_cookie_params('86400', '/', '.php', true, true);
            session_start();
            
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['password'] = $_POST['password'];

            $allowed_chars = '/^[a-zA-Z0-9.@ ]+$/';

            if (!preg_match($allowed_chars, $_SESSION['username'])) {
                $error_msg = "Username is invalid.";
            }
            else {
                $db_file = __DIR__ . '\forum_database.db';
                $db = new PDO('sqlite:' . $db_file);
                
                $user_check = $db -> prepare('SELECT * FROM users WHERE username = :user');
                $user_check -> bindParam(':user', $_SESSION['username']);
                $user_check -> execute();
                
                if ($row = $user_check -> fetch(PDO::FETCH_ASSOC)) {
                    if (!password_verify($_SESSION['password'], $row['password'])) {
                        $error_msg = "Password is incorrect.";

                    } else {
                        
                        $_SESSION['username'] = $row['username'];
                        
                        header('Location: home.php');
                    }
                }
                else {
                    $error_msg = "Username does not exist.";
                }
            }
        }

    ?>
    
    
    <form action="login.php" method="post">
        <h1>DiscussDen</h1>
        <h3>Join DiscussDen.</h3>
        
        <label for="username" class="placeholder">Username or Email</label>
        <input type="text" name="username" id="username" value="<?php if(isset($_SESSION['username'])) echo $_SESSION['username']?>" required> 
        <?php if (isset($error_msg) && ($error_msg == "Username does not exist." || $error_msg == "Username is invalid.")) echo '<label for="username" class="error">' . $error_msg . '</label>' ?>
        
        <label for="username" class="placeholder">Password</label>
        <input type="password" name="password" id="password" required> 
        <?php if (isset($error_msg) && $error_msg == "Password is incorrect.") echo '<label for="password" class="error">' . $error_msg . '</label>' ?>
        <a href="" class="forgot">Forgot Password?</a>
        <input type="submit" value="Log In">
        <hr>
        <a href="sign-up.php" class="create">Create an account</a>
    </form>
</body>
</html>