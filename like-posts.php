<?php
$db_file = __DIR__ . '\forum_database.db';
$db = new PDO('sqlite:' . $db_file);

$post_id = $_POST['post_id'];
$user_id = $_POST['user_id'];
$is_like = 1; // or 0 if the user unliked the post

$stmt = $db->prepare('SELECT * FROM post_likes WHERE post_id = :post_id AND user_id = :user_id');
$stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$like = $stmt->fetch(PDO::FETCH_ASSOC);

if ($like) {
    // The user already liked the post, so update the existing record
    $stmt = $db->prepare('UPDATE post_likes SET is_like = :is_like WHERE id = :id');
    $stmt->bindParam(':id', $like['id'], PDO::PARAM_INT);
    $stmt->bindParam(':is_like', $is_like, PDO::PARAM_INT);
    $stmt->execute();
} else {
    // The user hasn't liked the post yet, so insert a new record
    $stmt = $db->prepare('INSERT INTO post_likes (post_id, user_id, is_like) VALUES (:post_id, :user_id, :is_like)');
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':is_like', $is_like, PDO::PARAM_INT);
    $stmt->execute();
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>