<?php
/*
*******************************************************
SIGN-UP.PHP

- The sign up interface
- Create a new user here
********************************************************
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DisscussDen Sign Up</title>
    <link rel="icon" href="img/icon.png">
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <?php 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Restrict the username to these characters
            $allowed_chars = '/^[a-zA-Z0-9.@]+$/';
            
            // If username does not follow these characters, return error message
            if (!preg_match($allowed_chars, $_POST['username'])) {
                $error_msg = "Invalid username. Only letters, numbers, the period and the @ symbol are allowed.";
            }
            // If passwords in both fields do not match, return error message
            else if ($_POST['password_1'] != $_POST['password_2']) {
                $error_msg = "Passwords do not match.";
            }
            else {
                // assign the username to a variable
                $username = $_POST['username'];

                // access the database
                $db_file = __DIR__ . '\forum_database.db';
                $db = new PDO('sqlite:' . $db_file);

                // Check if username is unique
                $user_check = $db -> prepare('SELECT * FROM users WHERE username = :user');
                $user_check -> bindParam(':user', $username, PDO::PARAM_STR);
                $user_check -> execute();
                
                if ($user_check -> rowCount() > 0) {
                    $error_msg = "Username already exists.";
                }
                else {
                    // Hash the password
                    $password = password_hash($_POST['password_1'], PASSWORD_DEFAULT);

                    // Insert the data into a new row in the database
                    $insert_data = $db -> prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
                    $insert_data -> bindParam(':username', $username);
                    $insert_data -> bindParam(':password', $password);
                    $insert_data -> execute();

                    // Redirect to login page
                    header('Location: login');

                }
            }
        
        }

    ?>
    
    
    <form action="sign-up.php" method="post" autocomplete="off">
        <h1>Sign Up</h1>
        <h3>It's quick and easy.</h3>
        <hr>
        
        <label for="username" class="placeholder">Create a username (Maximum of 15 characters)</label>
        <input type="text" name="username" id="username" value="<?php if(isset($_POST['username'])) echo $_POST['username']?>" required maxlength=15> 
        <?php if (isset($error_msg) && ($error_msg == "Invalid username. Only letters, numbers, the period and the @ symbol are allowed." || $error_msg == "Username already exists.")) echo '<label for="username" class="error">' . $error_msg . '</label>' ?>
        
        <label for="username" class="placeholder">Create a password (Maximum of 18 characters)</label>
        <input type="password" name="password_1" id="" value="<?php if(isset($_POST['password_1'])) echo $_POST['password_1']?>" required maxlength=18> 
        
        <label for="username" class="placeholder">Verify password</label>
        <input type="password" name="password_2" id="password" required maxlength=18> 
        <?php if (isset($error_msg) && $error_msg == "Passwords do not match.") echo '<label for="password" class="error">' . $error_msg . '</label>' ?>

        <input type="submit" value="Sign Up" id="signup">
    </form>
</body>
</html>