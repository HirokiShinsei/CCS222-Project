<?php
/*
*******************************************************
EDIT-POST.PHP

- The post editing interface
********************************************************
*/

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a post</title>
    <link rel="icon" href="img/icon.png">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="createpost.css">
    
</head>
<body>
    <?php 
        // Include the header
        include_once "header.php";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Import the post into page if the post method is not from this page
            if (!isset($_POST['source'])) {
                
                // Get post content from id
                $stmt = $db -> prepare('SELECT * FROM posts WHERE id = :id');
                $stmt -> bindParam(':id', $_POST['id'], PDO::PARAM_INT);
                $stmt -> execute();
                $post = $stmt -> fetch(PDO::FETCH_ASSOC);

            } 
            // If the post method is from this page (i.e. the form below)
            else {
                
                // Get current date and time
                $date = new DateTime("now", new DateTimeZone("Asia/Manila"));
                $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                // update row of post (in table posts)
                $stmt = $db -> prepare('UPDATE posts SET title = :title, content = :content, date = :date WHERE id = :id');
                $stmt -> bindParam(':title', strip_tags($_POST['title']), PDO::PARAM_STR);
                $stmt -> bindParam(':content', strip_tags(nl2br($_POST['content']), ['<br>', '<br/>']), PDO::PARAM_STR);
                $stmt -> bindParam(':date', $date -> format('F j, Y g:i A'));
                $stmt -> bindParam(':id', $_POST['id']);
                $stmt -> execute();


                // Redirect to profile page
                header('Location: profile');
                exit;
            }
        }
    ?>
    <div class="post">
        <h1 class="post-head">Edit post</h1>
        <form action="edit-post.php" method="post" class="form" autocomplete="off">
            <input type="hidden" name="id" value="<?php echo $_POST['id']?>">
            <input type="hidden" name="source" value=".">
            <input type="text" name="title" class="title" placeholder="Place your title here (maximum of 100 characters)" required value="<?php echo $post['title']?>" maxlength=100>
            <textarea name="content" class="content" placeholder="Write something..." required><?php echo str_replace("<br />", "", $post['content'])?></textarea>
            <input type="submit" value="Edit">
        </form>
    </div>
</body>
</html>