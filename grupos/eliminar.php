<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);
require_once("../clases/GrupoEstudiantil.php");

if (isset($_GET['id'])) {
    $grupoObj = new GrupoEstudiantil($conexion);
    $grupoObj->eliminar($_GET['id']);
}
header("Location: listar.php?msg=eliminado");
exit;
?>