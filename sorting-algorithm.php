<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('Location: 403-Forbidden.html');
    exit();
}

session_start();

if ($_POST['sortMethod'] == 'hot')
    $_SESSION['sortMethod'] = 'hot';

else if ($_POST['sortMethod'] == 'top')
    $_SESSION['sortMethod'] = 'top';

else $_SESSION['sortMethod'] = 'new';

echo($_SESSION['sortMethod']);

?>