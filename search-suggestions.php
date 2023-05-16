<?php
/*
*******************************************************
SEARCH-SUGGESTIONS.PHP

- Returns the search suggestions
********************************************************
*/

// Prevents direct url attack (redirect to 403 Forbidden)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('Location: 403-Forbidden.html');
    exit();
}

// Access the database
$db_file = __DIR__ . '\forum_database.db';
$db = new PDO('sqlite:' . $db_file);

// Get the search term from submitted data
$searchTerm = $_POST['searchTerm'];

// Get post searches
$stmt = $db->prepare("SELECT title FROM posts WHERE title LIKE :term LIMIT 5");
$stmt->bindValue(':term', '%' . $searchTerm . '%');
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Get user searches
$stmt = $db -> prepare('SELECT username FROM users WHERE username LIKE :user LIMIT 5');
$stmt -> bindValue(':user', '%' . $searchTerm . '%');
$stmt -> execute();
$user_results = $stmt -> fetchAll(PDO::FETCH_COLUMN);

// Return the combination of both search queries
$final_results = array_merge($results, $user_results);

// sort merge based on closest result to the search value
function compareValue($a, $b, $searchTerm) {
    $aDistance = levenshtein($a, $searchTerm);
    $bDistance = levenshtein($b, $searchTerm);

    return $aDistance - $bDistance;
}

// Sort the values in the array depending on how close they are to the search term
usort($final_results, function($a, $b) use ($searchTerm) {
    return compareValue($a, $b, $searchTerm);
});

// Return the search results array
echo json_encode($final_results);
?>