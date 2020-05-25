<?php

session_start();

if (isset($_SESSION['logged']) != true) {
    header("location: login.php");
}
//Détruit les sessions
$_SESSION = array();
session_destroy();

