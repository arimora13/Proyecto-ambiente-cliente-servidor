<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);
require_once("../clases/Institucion.php");

if (isset($_GET['id'])) {
    $institucionObj = new Institucion($conexion);
    $institucionObj->eliminar($_GET['id']);
}
header("Location: listar.php?msg=eliminado");
exit;
?>