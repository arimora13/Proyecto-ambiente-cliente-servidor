<?php
require_once("../conexion.php");
validarSesion();
require_once("../clases/TipoCultivo.php");

$tipoObj = new TipoCultivo($conexion);
$listaTipos = $tipoObj->listar();

include(__DIR__ . "/vistas/listar_vista.html");