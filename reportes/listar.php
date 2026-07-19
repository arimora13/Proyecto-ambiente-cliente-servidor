<?php
require_once("../conexion.php"); //conexion
validarSesion(); //sesion validada
validarRol(["Administrador","Docente"]); //validacion
require_once("../clases/Reporte.php"); // cargar clase

$reporteObj =new Reporte($conexion); //generacion del objeto
$lista = $reporteObj->listar();  //llamar a listar

//mostrar
include(__DIR__ . "/vistas/listar_vista.html");
