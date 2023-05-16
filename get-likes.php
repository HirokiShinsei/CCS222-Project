<?php 
/*
*******************************************************
GET-LIKES.PHP

- The upvote system
********************************************************
*/

// Prevents direct url attack (redirect to 403 Forbidden)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('Location: 403-Forbidden.html');
    exit();
}

// Start the session
session_start();

// Start catching output warnings
ob_start();

// Access the database
$db_file = __DIR__ . '\forum_database.db';
$db = new PDO('sqlite:' . $db_file);

// Get the post from submitted id
$stmt = $db -> prepare('SELECT * FROM posts WHERE id = :post_id');
$stmt -> bindParam(':post_id', $_POST['post_id']);
$stmt -> execute();

$post = $stmt -> fetch(PDO::FETCH_ASSOC);

// If there is at least one upvote
if (json_decode($post['likes']) !== NULL) {

    // Get the upvotes
    $likes = json_decode($post['likes']);

    // If the user has upvoted already, delete the user from the array; otherwise, add the user to the array
    if (!(($index = array_search($_SESSION['username'], $likes)) === false)) {
        array_splice($likes, $index, 1);
    } else {
        array_unshift($likes, $_SESSION['username']);
    }
    
} else {
    // If there are no upvotes, add the user as the first upvote
    $likes = array($_SESSION['username']);
}

// Update the upvote array in the database
$stmt = $db -> prepare('UPDATE posts SET likes = :likes WHERE id = :post_id');
$stmt -> bindParam(':likes', json_encode($likes));
ob_clean();
$stmt -> bindParam(':post_id', $_POST['post_id']);
$stmt -> execute();

// Return the number of upvotes
echo count($likes);
?>