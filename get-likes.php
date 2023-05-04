<?php
require_once "generate_like_text_function.php"; // Include the generateLikeText function from your previous code

if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    $db_file = __DIR__ . '\forum_database.db';
    $db = new PDO('sqlite:' . $db_file);

    $stmt = $db->prepare('SELECT users.user_id, users.username FROM post_likes INNER JOIN users ON post_likes.user_id = users.user_id WHERE post_likes.post_id = :post_id AND post_likes.is_like = 1');
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    $users_who_liked = $stmt->fetchAll(PDO::FETCH_ASSOC);

    session_start();
    echo generateLikeText($users_who_liked, isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null);
}
