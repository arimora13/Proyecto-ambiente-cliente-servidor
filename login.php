<?php
require_once("conexion.php");
require_once("clases/Usuario.php");

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = trim($_POST['correo']);
    $clave  = trim($_POST['clave']);

    $usuario = new Usuario($conexion);
    $datos = $usuario->validarLogin($correo, $clave);

    if ($datos) {
        $_SESSION['id_usuario']  = $datos['ID_USUARIO'];
        $_SESSION['nombre']      = $datos['NOMBRE'];
        $_SESSION['nombre_rol']  = $datos['NOMBRE_ROL'];
        header("Location: menu.php");
        exit;
    } else {
        $error = "Correo o contrasena incorrectos.";
    }
}

include(__DIR__ . "/vistas/login_vista.html");
