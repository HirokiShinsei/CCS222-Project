<?php
$db_file = __DIR__ . '\forum_database.db';
$db = new PDO('sqlite:' . $db_file);

// Initialize $results as an empty array
$results = [];

// Check if search is set and not empty
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $searchTerm = $_POST['search'];

    // Get post searches
    $stmt = $db->prepare("SELECT * FROM posts WHERE title LIKE :term OR content LIKE :term");
    $stmt->bindValue(':term', '%' . $searchTerm . '%');
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <link rel="icon" href="img/icon.png">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="post-boxes.css">
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <?php include_once "header.php" ?>

    <h1>Search Results</h1>

    <?php if (!empty($results)) : ?>
        <?php foreach ($results as $result) : ?>
            <div class="post-container">
                <h2><?php echo $result['title']; ?> </h2>
                <p><?php echo $result['content']; ?></p>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>No results found.</p>
    <?php endif; ?>
</body>
</html>
