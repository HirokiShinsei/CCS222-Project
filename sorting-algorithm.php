<?php
session_start();

if ($_POST['sortMethod'] == 'hot')
    $_SESSION['sortMethod'] = 'hot';

else if ($_POST['sortMethod'] == 'top')
    $_SESSION['sortMethod'] = 'top';

else $_SESSION['sortMethod'] = 'new';

echo($_SESSION['sortMethod']);

?>