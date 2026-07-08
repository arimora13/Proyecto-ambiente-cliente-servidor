<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);
require_once("../clases/Huerta.php");

if (isset($_GET['id'])) {
    $huertaObj = new Huerta($conexion);
    $huertaObj->eliminar($_GET['id']);
}
header("Location: listar.php?msg=eliminado");
exit;
?>