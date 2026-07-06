<?php
require_once("../conexion.php");
validarSesion();
require_once("../clases/Riego.php");

if (isset($_GET['id'])) {
    $riegoObj = new Riego($conexion);
    $riegoObj->eliminar($_GET['id']);
}
header("Location: listar.php?msg=eliminado");
exit;
?>
