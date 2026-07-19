<?php
require_once("../conexion.php"); //conexion bd
validarSesion(); //sesion iniciada
validarRol(["Administrador","Docente"]); //validacion rol
require_once("../clases/Reporte.php"); //carga clase reporte

$reporteObj = new Reporte($conexion); //creacion del objeto

if (!isset($_GET['id'])) { //si no existe
    header("Location: listar.php"); 
    //regresa el listado
    exit;
} 

//si el formulario es enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reporteObj->editar(
        $_POST['id'], 
        $_POST['idHuerta'], 
        $_POST['fecha'], 
        $_POST['periodo'], 
        $_POST['descripcion']);

    header("Location: listar.php?msg=editado");
    exit;
    //actualiza la informacion del reporte y manda al listado
}

//lista el reporte segun el buscado por id
$datos = $reporteObj->obtenerPorId($_GET['id']);
if (!$datos) {
    header("Location: listar.php");
    exit;
} 

$listaHuertas = $reporteObj->listarHuertas();//lista el nuevo reporte

include(__DIR__ . "/vistas/editar_vista.html");
