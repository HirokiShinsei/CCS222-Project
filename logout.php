<?php 
/*
*******************************************************
LOGOUT.PHP

- The logout function (simply destroys the session)
********************************************************
*/

// Prevent direct url attack (redirect to 403 Forbidden)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('Location: 403-Forbidden.html');
    exit();
}
    // starts and destroys the session
    session_start();
    session_destroy();
?>