<?php
require_once("../conexion.php");
validarSesion();
require_once("../clases/Cultivo.php");

$cultivoObj = new Cultivo($conexion);
$listaCultivos = $cultivoObj->listar();

$verSeguimiento = null;
$historial = [];
if (isset($_GET['seguimiento'])) {
    $verSeguimiento = $_GET['seguimiento'];
    $historial = $cultivoObj->seguimiento($verSeguimiento);
}

include(__DIR__ . "/vistas/listar_vista.html");
