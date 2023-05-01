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
            
            $_SESSION['firstname'] = $_POST['firstname'];
            $_SESSION['lastname'] = $_POST['lastname'];

            $allowed_chars = '/^[a-zA-Z ]+$/';

            if (!preg_match($allowed_chars, $_SESSION['firstname'])) {
                $error_msg = "Invalid first name.";
            }
            else if (!preg_match($allowed_chars, $_SESSION['lastname'])) {
                $error_msg = "Invalid last name.";
            }
            else {
                $allowed_chars = '/^[a-zA-Z0-9.@]+$/';
            
                if (!preg_match($allowed_chars, $_POST['username'])) {
                    $error_msg = "Invalid username.";
                }
                else if ($_POST['password_1'] != $_POST['password_2']) {
                    $error_msg = "Passwords do not match.";
                }
                else {
                    $_SESSION['username'] = $_POST['username'];
                    $email = $_POST['username'] . "@discussden.com";

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

                        $user_id = "user_" . ($user_count -> fetchColumn() + 1);

                        $insert_data = $db -> prepare('INSERT INTO users (user_id, username, password, firstname, lastname) VALUES (:id, :username, :password, :firstname, :lastname)');
                        $insert_data -> bindParam(':id', $user_id);
                        $insert_data -> bindParam(':username', $_SESSION['username']);
                        $insert_data -> bindParam(':password', $_SESSION['password']);
                        $insert_data -> bindParam(':firstname', $_SESSION['firstname']);
                        $insert_data -> bindParam(':lastname', $_SESSION['lastname']);
                        $insert_data -> execute();

                        session_destroy();
                        setcookie("username", $_SESSION['username'], time() + (86400 * 7), "/");
                        setcookie("firstname", $_SESSION['firstname'], time() + (86400 * 7), "/");
                        header('Location: login.php');

                    }
                }
            }
        }

    ?>
    
    
    <form action="sign-up.php" method="post">
        <h1>Sign Up</h1>
        <h3>It's quick and easy.</h3>
        <hr>
        
        <div>
            <label for="username" class="placeholder">First Name</label>
            <label for="username" class="placeholder">Last Name</label>
            
            <input type="text" name="firstname" id="firstname" value="<?php if(isset($_SESSION['firstname'])) echo $_SESSION['firstname']?>" required> 
            <input type="text" name="lastname" id="lastname" value="<?php if(isset($_SESSION['lastname'])) echo $_SESSION['lastname']?>" required> 
            
            <?php if (isset($error_msg) && $error_msg == "Invalid first name.") echo '<label for="firstname" class="error">' . $error_msg . '</label>' ?>
            <?php if (isset($error_msg) && $error_msg == "Invalid last name.") echo '<label for="lastname" class="error">' . $error_msg . '</label>' ?>
        </div>
        
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