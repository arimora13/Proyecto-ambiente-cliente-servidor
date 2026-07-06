<?php
require_once("conexion.php");

if (isset($_SESSION['id_usuario'])) {
    header("Location: menu.php");
} else {
    header("Location: login.php");
}
exit;
?>
