<?php
    session_start();
    if(!isset($_SESSION['user'])) { // Check if the user is connected, if not redirect him to the connexion page
        header("Location: index.php");
        exit();
    }
?>