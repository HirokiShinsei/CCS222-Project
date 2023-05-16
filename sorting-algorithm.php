<?php
/*
***************************************************************
SORTING-ALGORITHM.PHP

- The sorting algorithm
- Sort according to hot (highest upvotes in the past two days)
- Sort according to new (most recent posts)
- Sort according to top (highest upvotes of all time)
***************************************************************
*/

// Prevents direct url attack (redirect to 403 Forbidden)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('Location: 403-Forbidden.html');
    exit();
}

// Start the session
session_start();

// Set the session sorting method according to submitted data
if ($_POST['sortMethod'] == 'hot')
    $_SESSION['sortMethod'] = 'hot';

else if ($_POST['sortMethod'] == 'top')
    $_SESSION['sortMethod'] = 'top';

else $_SESSION['sortMethod'] = 'new';

echo($_SESSION['sortMethod']);

?>