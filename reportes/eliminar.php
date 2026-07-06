<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador","Docente"]);
require_once("../clases/Reporte.php");

if (isset($_GET['id'])) {
    $reporteObj = new Reporte($conexion);
    $reporteObj->eliminar($_GET['id']);
}
header("Location: listar.php?msg=eliminado");
exit;
?>
