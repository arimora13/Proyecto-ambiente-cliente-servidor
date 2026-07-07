<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador","Docente"]);
require_once("../clases/Reporte.php");

$reporteObj = new Reporte($conexion);
$lista = $reporteObj->listar();

include(__DIR__ . "/vistas/listar_vista.html");
