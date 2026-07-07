<?php
require_once("../conexion.php");
validarSesion();
require_once("../clases/Fertilizacion.php");

$fertilizacionObj = new Fertilizacion($conexion);
$lista = $fertilizacionObj->listar();

include(__DIR__ . "/vistas/listar_vista.html");
