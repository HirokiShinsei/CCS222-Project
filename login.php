<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum Website Sign In</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <?php 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            session_start();
            
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['password'] = $_POST['password'];

            $allowed_chars = '/^[a-zA-Z ]+$/';

            if (!preg_match($allowed_chars, $_SESSION['firstname'])) {
                $error_msg = "Username does not exist.";
            }
            else {
                $db_file = __DIR__ . './forum_database.db';
                $db = new PDO('sqlite:' . $db_file);
                
                $user_check = $db -> prepare('SELECT * FROM users WHERE username = :user');
                $user_check -> bindParam(':user', $_SESSION['username'], PDO::PARAM_STR);
                $user_check -> execute();

                if ($user_check -> rowCount() == 1) {
                    $row = $user_check -> fetch(PDO::FETCH_ASSOC);

                    if (!password_verify($_SESSION['password'], $row['password'])) {
                        $error_msg = "Password is incorrect.";
                    } else {
                        header('Location: home.php');
                    }
                }
                else {
                    $error_msg = "Username does not exist.";
                }
            }
        }

    ?>
    
    
    <form action="sign-in.php" method="post">
        <h1>Login</h1>
        
        <input type="text" name="username" id="username" placeholder="Username" value="<?php if(isset($_SESSION['username'])) echo $_SESSION['username']?>" required> 
        <?php if (isset($error_msg) && $error_msg == "Username does not exist.") echo '<label for="username">' . $error_msg . '</label>' ?>
        
        <input type="text" name="password" id="password" placeholder="Password" required> 
        <?php if (isset($error_msg) && $error_msg == "Password is incorrect.") echo '<label for="password">' . $error_msg . '</label>' ?>
        <input type="submit" value="Next">
        <a href="sign-in.php">I don't have an account</a>
    </form>
</body>
</html>