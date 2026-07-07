<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador","Docente"]);
require_once("../clases/Reporte.php");

$reporteObj = new Reporte($conexion);

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reporteObj->editar($_POST['id'], $_POST['idHuerta'], $_POST['fecha'], $_POST['periodo'], $_POST['descripcion']);
    header("Location: listar.php?msg=editado");
    exit;
}

$datos = $reporteObj->obtenerPorId($_GET['id']);
if (!$datos) {
    header("Location: listar.php");
    exit;
}
$listaHuertas = $reporteObj->listarHuertas();

include(__DIR__ . "/vistas/editar_vista.html");
