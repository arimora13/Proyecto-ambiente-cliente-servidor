<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);
require_once("../clases/Huerta.php");

$huertaObj = new Huerta($conexion);
$listaHuertas = $huertaObj->listar();

include(__DIR__ . "/vistas/listar_vista.html");