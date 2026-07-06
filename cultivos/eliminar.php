<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador","Docente"]);
require_once("../clases/Cultivo.php");

if (isset($_GET['id'])) {
    $cultivoObj = new Cultivo($conexion);
    $cultivoObj->eliminar($_GET['id']);
}
header("Location: listar.php?msg=eliminado");
exit;
?>
