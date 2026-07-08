<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);
require_once("../clases/Huerta.php");

$huertaObj = new Huerta($conexion);

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $huertaObj->editar($_POST['id'], $_POST['idInstitucion'], $_POST['idEstado'], $_POST['nombre'], $_POST['areaM2'], $_POST['descripcion']);
    header("Location: listar.php?msg=editado");
    exit;
}

$datos = $huertaObj->obtenerPorId($_GET['id']);
if (!$datos) {
    header("Location: listar.php");
    exit;
}
$listaInstituciones = $huertaObj->listarInstituciones();
$listaEstados = $huertaObj->listarEstados();

include(__DIR__ . "/vistas/editar_vista.html");