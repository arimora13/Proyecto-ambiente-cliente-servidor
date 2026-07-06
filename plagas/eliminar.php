<?php
require_once("../conexion.php");
validarSesion();
require_once("../clases/Plaga.php");

if (isset($_GET['id'])) {
    $plagaObj = new Plaga($conexion);
    $plagaObj->eliminar($_GET['id']);
}
header("Location: listar.php?msg=eliminado");
exit;
?>
