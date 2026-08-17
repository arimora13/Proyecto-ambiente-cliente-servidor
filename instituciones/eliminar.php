<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);
require_once("../clases/Institucion.php");

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $institucionObj = new Institucion($conexion);

    if ($institucionObj->eliminar($_GET['id'])) {
        header("Location: listar.php?msg=eliminado");
    } else {
        header("Location: listar.php?msg=error");
    }
    exit;
} else {
    header("Location: listar.php");
    exit;
}
?>