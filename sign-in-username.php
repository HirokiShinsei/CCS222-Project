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
            
            $allowed_chars = '/^[a-zA-Z0-9.]+$/';
            
            if (!preg_match($allowed_chars, $_POST['username'])) {
                $error_msg = "Invalid username.";
            }
            else if ($_POST['password_1'] != $_POST['password_2']) {
                $error_msg = "Passwords do not match.";
            }
            else {
                $_SESSION['username'] = $_POST['username'] . "@forum.com";

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
                    header('Location: login.php');

                }
            }
        }

    ?>

    <form action="sign-in-username.php" method="post">
        <h1>Sign In</h1>

        <input type="text" name="username" id="username" placeholder="User Name" value="<?php if(isset($_POST['username'])) echo $_POST['username']?>" required> 
        <?php if (isset($error_msg) && ($error_msg == "Invalid username." || $error_msg == "Username already exists.")) echo '<label for="username">' . $error_msg . '</label>' ?>
        
        <input type="password" name="password_1" id="" placeholder="Password" value="<?php if(isset($_POST['password_1'])) echo $_POST['password_1']?>" required> 
        <input type="password" name="password_2" id="password" placeholder="Verify Password" required> 
        <?php if (isset($error_msg) && $error_msg == "Passwords do not match.") echo '<label for="password">' . $error_msg . '</label>' ?>

        <button type="button" onclick="history.back()">Back</button>
        <input type="submit" value="Sign Up">
    </form>

</body>
</html>