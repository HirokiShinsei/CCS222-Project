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
                header('location: sign-in-username.php');
            }
        }

    ?>
    
    
    <form action="sign-in.php" method="post">
        <h1>Sign In</h1>
        
        <input type="text" name="firstname" id="firstname" placeholder="First Name" value="<?php if(isset($_SESSION['firstname'])) echo $_SESSION['firstname']?>" required> 
        <?php if (isset($error_msg) && $error_msg == "Invalid first name.") echo '<label for="firstname">' . $error_msg . '</label>' ?>
        
        <input type="text" name="lastname" id="lastname" placeholder="Last Name" value="<?php if(isset($_SESSION['lastname'])) echo $_SESSION['lastname']?>" required> 
        <?php if (isset($error_msg) && $error_msg == "Invalid last name.") echo '<label for="lastname">' . $error_msg . '</label>' ?>
        <input type="submit" value="Next">
    </form>
</body>
</html>