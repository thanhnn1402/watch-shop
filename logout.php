<?php 
    $conn = mysqli_connect('localhost', 'root', '', 'watch-shop') or die('Couldn\'t connect to Database');

    session_start();

    if(isset($_SESSION['admin_logged'])) {
        unset($_SESSION['admin_logged']);
    }

    if(isset($_SESSION['user_logged'])) {
        unset($_SESSION['user_logged']);
    }

    if(isset($_SESSION['previous-page'])) {
        unset($_SESSION['previous-page']);
    }

    header('location: ./login.php');
?>