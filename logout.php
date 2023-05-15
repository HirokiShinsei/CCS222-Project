<?php 
if (empty($_SERVER['HTTP_REFERER']) || !strpos($_SERVER['HTTP_REFERER'], 'localhost/CCS222-Project')) {
    header('Location: 403-Forbidden.html');
    exit();
}

    session_start();
    session_destroy();
?>