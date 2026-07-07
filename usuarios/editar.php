<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);
require_once("../clases/Usuario.php");

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

$usuarioObj = new Usuario($conexion);
$datos = $usuarioObj->obtenerPorId($_GET['id']);

if (!$datos) {
    header("Location: listar.php");
    exit;
}

$listaRoles   = $conexion->query("SELECT ID_ROL, NOMBRE_ROL FROM ROL")->fetchAll(PDO::FETCH_ASSOC);
$listaEstados = $conexion->query("SELECT ID_ESTADO, NOMBRE_ESTADO FROM ESTADO")->fetchAll(PDO::FETCH_ASSOC);

include(__DIR__ . "/vistas/editar_vista.html");
