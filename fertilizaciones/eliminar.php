<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador","Docente"]);

require_once("../clases/Fertilizacion.php");

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $fertilizacionObj = new Fertilizacion($conexion);
    $fertilizacionObj->eliminar($_GET['id'] ?? '');
}
header("Location: listar.php?msg=eliminado");
exit;

//Si se mete un id valio se envia de vuelta al listado
header("Location: listar.php");
exit;
?>
