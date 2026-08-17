<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);
require_once("../clases/Usuario.php");

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $usuario = new Usuario($conexion);
    $usuario->eliminar($_GET['id']);
    header("Location: ../usuarios/listar.php?msg=eliminado");
    exit;
} else {
    header("Location: ../usuarios/listar.php");
    exit;
}
?>
