<?php
if (empty($_SERVER['HTTP_REFERER']) || !strpos($_SERVER['HTTP_REFERER'], 'localhost/CCS222-Project')) {
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