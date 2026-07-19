<?php
require_once("../conexion.php"); //conexion bd
validarSesion(); //sesion iniciada?
validarRol(["Administrador","Docente"]); //permisos
require_once("../clases/Reporte.php"); //carga clase

$reporteObj= new Reporte($conexion); //generacion de objeto
$listaHuertas= $reporteObj->listarHuertas(); //llamar al listado de guertas pero desde reportes

if ($_SERVER['REQUEST_METHOD'] == 'POST') { //tras enviar el fomrulario
    $reporteObj->guardar(
        $_POST['idHuerta'], 
        $_POST['fecha'], 
        $_POST['periodo'], 
        $_POST['descripcion']);
    header("Location: listar.php?msg=guardado");
    //mostrar mensaje y salir
    exit;
}

include(__DIR__ . "/vistas/registrar_vista.html");
