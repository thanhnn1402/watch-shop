<?php 
    if (!defined("IN_SITE")) die ("The request not found");
    session_start();

    // Gan session
    function session_set($key, $val) {
        $_SESSION[$key] = $val; 
    }

    // Lay session
    function session_get($key) {
        return (isset($_SESSION[$key])) ? $_SESSION[$key] : false;
    }

    // Xoa session
    function session_delete($key) {
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
        }
    }
?>
