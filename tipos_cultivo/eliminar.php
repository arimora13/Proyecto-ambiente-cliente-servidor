<?php
require_once("../conexion.php");
validarSesion();
require_once("../clases/TipoCultivo.php");

if (isset($_GET['id'])) {
    $tipoObj = new TipoCultivo($conexion);
    $tipoObj->eliminar($_GET['id']);
}
header("Location: listar.php?msg=eliminado");
exit;
?>