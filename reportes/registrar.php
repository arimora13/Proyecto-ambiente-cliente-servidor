<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador","Docente"]);
require_once("../clases/Reporte.php");

$reporteObj = new Reporte($conexion);
$listaHuertas = $reporteObj->listarHuertas();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reporteObj->guardar($_POST['idHuerta'], $_POST['fecha'], $_POST['periodo'], $_POST['descripcion']);
    header("Location: listar.php?msg=guardado");
    exit;
}

include(__DIR__ . "/vistas/registrar_vista.html");
