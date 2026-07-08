<?php
require_once("../conexion.php");
validarSesion();
require_once("../clases/TipoCultivo.php");

$tipoObj = new TipoCultivo($conexion);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipoObj->guardar($_POST['nombre'], $_POST['nombreCientifico'], $_POST['tiempoCosecha'], $_POST['frecuenciaRiego'], $_POST['frecuenciaFertilizacion'], $_POST['observaciones']);
    header("Location: listar.php?msg=guardado");
    exit;
}

include(__DIR__ . "/vistas/registrar_vista.html");