<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DisscussDen Sign Up</title>
    <link rel="icon" href="img/icon.png">
    <link rel="stylesheet" href="sign-up.css">
</head>
<body>

    <?php 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            session_start();

            $allowed_chars = '/^[a-zA-Z0-9.@]+$/';
        
            if (!preg_match($allowed_chars, $_POST['username'])) {
                $error_msg = "Invalid username.";
            }
            else if ($_POST['password_1'] != $_POST['password_2']) {
                $error_msg = "Passwords do not match.";
            }
            else {
                $_SESSION['username'] = trim($_POST['username']);
                $_SESSION['user-link'] = "localhost/CCS222-Project/" . $_SESSION['username'];

                // SQLite Database Access
                $db_file = __DIR__ . '\forum_database.db';
                $db = new PDO('sqlite:' . $db_file);

                $user_check = $db -> prepare('SELECT * FROM users WHERE username = :user');
                $user_check -> bindParam(':user', $_SESSION['username'], PDO::PARAM_STR);
                $user_check -> execute();
                
                if ($user_check -> rowCount() > 0) {
                    $error_msg = "Username already exists.";
                }
                else {
                    $_SESSION['password'] = password_hash($_POST['password_1'], PASSWORD_DEFAULT);

                    $user_count = $db -> prepare('SELECT COUNT(*) FROM users');
                    $user_count -> execute();

                    $insert_data = $db -> prepare('INSERT INTO users (username, password, link) VALUES (:username, :password, :link)');
                    $insert_data -> bindParam(':username', $_SESSION['username']);
                    $insert_data -> bindParam(':password', $_SESSION['password']);
                    $insert_data -> bindParam(':link', $_SESSION['user-link']);
                    $insert_data -> execute();

                    session_destroy();
                    header('Location: login.php');

                }
            }
        
        }

    ?>
    
    
    <form action="sign-up.php" method="post">
        <h1>Sign Up</h1>
        <h3>It's quick and easy.</h3>
        <hr>
        
        <label for="username" class="placeholder">Create a username</label>
        <input type="text" name="username" id="username" value="<?php if(isset($_POST['username'])) echo $_POST['username']?>" required> 
        <?php if (isset($error_msg) && ($error_msg == "Invalid username." || $error_msg == "Username already exists.")) echo '<label for="username" class="error">' . $error_msg . '</label>' ?>
        
        <label for="username" class="placeholder">Create a password</label>
        <input type="password" name="password_1" id="" value="<?php if(isset($_POST['password_1'])) echo $_POST['password_1']?>" required> 
        
        <label for="username" class="placeholder">Verify password</label>
        <input type="password" name="password_2" id="password" required> 
        <?php if (isset($error_msg) && $error_msg == "Passwords do not match.") echo '<label for="password" class="error">' . $error_msg . '</label>' ?>

        <input type="submit" value="Sign Up">
    </form>
</body>
</html>