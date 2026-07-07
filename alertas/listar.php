<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador","Docente"]);
require_once("../clases/Alerta.php");

$alertaObj = new Alerta($conexion);
$lista = $alertaObj->listar();

include(__DIR__ . "/vistas/listar_vista.html");
