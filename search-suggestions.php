<?php

$db_file = __DIR__ . '\forum_database.db';
$db = new PDO('sqlite:' . $db_file);

$searchTerm = $_POST['searchTerm'];

// Get post searches
$stmt = $db->prepare("SELECT title FROM posts WHERE title LIKE :term LIMIT 10");
$stmt->bindValue(':term', '%' . $searchTerm . '%');
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Get user searches
$stmt = $db -> prepare('SELECT username FROM users WHERE username LIKE :user LIMIT 10');
$stmt -> bindValue(':user', '%' . $searchTerm . '%');
$stmt -> execute();
$user_results = $stmt -> fetchAll(PDO::FETCH_COLUMN);

$final_results = array_merge($results, $user_results);

// sort merge based on closest result to the search value
function compareValue($a, $b, $searchTerm) {
    $aDistance = levenshtein($a, $searchTerm);
    $bDistance = levenshtein($b, $searchTerm);

    return $aDistance - $bDistance;
}

usort($final_results, function($a, $b) use ($searchTerm) {
    return compareValue($a, $b, $searchTerm);
});

echo json_encode($final_results);
?>