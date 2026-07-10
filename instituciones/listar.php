<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);
require_once("../clases/Institucion.php");

$institucionObj = new Institucion($conexion);
$listaInstituciones = $institucionObj->listar();

include(__DIR__ . "/vistas/listar_vista.html");