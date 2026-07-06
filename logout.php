<?php
require_once("conexion.php");
session_unset();
session_destroy();
header("Location: login.php");
exit;
?>
