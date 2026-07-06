<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador","Docente"]);
require_once("../clases/Alerta.php");

if (isset($_GET['id'])) {
    $alertaObj = new Alerta($conexion);
    $alertaObj->eliminar($_GET['id']);
}
header("Location: listar.php?msg=eliminada");
exit;
?>
