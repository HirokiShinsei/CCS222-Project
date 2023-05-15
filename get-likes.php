<?php 
if (empty($_SERVER['HTTP_REFERER']) || !strpos($_SERVER['HTTP_REFERER'], 'localhost/CCS222-Project')) {
    header('Location: 403-Forbidden.html');
    exit();
}

session_start();
ob_start();

$db_file = __DIR__ . '\forum_database.db';
$db = new PDO('sqlite:' . $db_file);

$stmt = $db -> prepare('SELECT * FROM posts WHERE id = :post_id');
$stmt -> bindParam(':post_id', $_POST['post_id']);
$stmt -> execute();

$post = $stmt -> fetch(PDO::FETCH_ASSOC);

if (json_decode($post['likes']) !== NULL) {
    $likes = json_decode($post['likes']);
    
    if (!(($index = array_search($_SESSION['username'], $likes)) === false)) {
        array_splice($likes, $index, 1);
    } else {
        array_unshift($likes, $_SESSION['username']);
    }
    
} else {
    $likes = array($_SESSION['username']);
}

$stmt = $db -> prepare('UPDATE posts SET likes = :likes WHERE id = :post_id');
$stmt -> bindParam(':likes', json_encode($likes));
ob_clean();
$stmt -> bindParam(':post_id', $_POST['post_id']);
$stmt -> execute();

echo count($likes);
?>