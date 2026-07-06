<?php
require_once("../conexion.php");
validarSesion();
require_once("../clases/Fertilizacion.php");

if (isset($_GET['id'])) {
    $fertilizacionObj = new Fertilizacion($conexion);
    $fertilizacionObj->eliminar($_GET['id']);
}
header("Location: listar.php?msg=eliminado");
exit;
?>
