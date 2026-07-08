<?php
require_once("../conexion.php");
validarSesion();
require_once("../clases/TipoCultivo.php");

$tipoObj = new TipoCultivo($conexion);

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipoObj->editar($_POST['id'], $_POST['nombre'], $_POST['nombreCientifico'], $_POST['tiempoCosecha'], $_POST['frecuenciaRiego'], $_POST['frecuenciaFertilizacion'], $_POST['observaciones']);
    header("Location: listar.php?msg=editado");
    exit;
}

$datos = $tipoObj->obtenerPorId($_GET['id']);
if (!$datos) {
    header("Location: listar.php");
    exit;
}

include(__DIR__ . "/vistas/editar_vista.html");