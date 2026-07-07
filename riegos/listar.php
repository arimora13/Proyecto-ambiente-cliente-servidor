<?php
require_once("../conexion.php");
validarSesion();
require_once("../clases/Riego.php");

$riegoObj = new Riego($conexion);
$lista = $riegoObj->listar();

include(__DIR__ . "/vistas/listar_vista.html");
