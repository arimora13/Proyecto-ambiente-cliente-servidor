<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);
require_once("../clases/Usuario.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idRol      = $_POST['idRol'];
    $idEstado   = $_POST['idEstado'];
    $nombre     = trim($_POST['nombre']);
    $apPaterno  = trim($_POST['apellidoPaterno']);
    $apMaterno  = trim($_POST['apellidoMaterno']);
    $clave      = trim($_POST['clave']);
    $correo     = trim($_POST['correo']);

    $usuario = new Usuario($conexion);
    if ($usuario->guardar($idRol, $idEstado, $nombre, $apPaterno, $apMaterno, $clave, $correo)) {
        header("Location: ../usuarios/listar.php?msg=guardado");
    } else {
        header("Location: ../usuarios/registrar.php?msg=error");
    }
    exit;
}
?>
