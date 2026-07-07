<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);
require_once("../clases/Usuario.php");

$usuario = new Usuario($conexion);
$listaUsuarios = $usuario->listar();

include(__DIR__ . "/vistas/listar_vista.html");
