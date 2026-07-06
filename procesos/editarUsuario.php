<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);
require_once("../clases/Usuario.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id         = $_POST['id'];
    $idRol      = $_POST['idRol'];
    $idEstado   = $_POST['idEstado'];
    $nombre     = trim($_POST['nombre']);
    $apPaterno  = trim($_POST['apellidoPaterno']);
    $apMaterno  = trim($_POST['apellidoMaterno']);
    $correo     = trim($_POST['correo']);

    $usuario = new Usuario($conexion);
    $usuario->editar($id, $idRol, $idEstado, $nombre, $apPaterno, $apMaterno, $correo);

    // Si se escribio una nueva clave, se actualiza aparte
    if (isset($_POST['clave']) && trim($_POST['clave']) !== "") {
        $usuario->cambiarClave($id, trim($_POST['clave']));
    }

    header("Location: ../usuarios/listar.php?msg=editado");
    exit;
}
?>
