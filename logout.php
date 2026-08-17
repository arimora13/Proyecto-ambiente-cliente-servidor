<?php
require_once("conexion.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = array();
session_unset();
session_destroy();

header("Location: login.php");
exit;
?>



