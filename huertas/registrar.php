<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);
require_once("../clases/Huerta.php");

$huertaObj = new Huerta($conexion);
$listaInstituciones = $huertaObj->listarInstituciones();
$listaEstados = $huertaObj->listarEstados();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $huertaObj->guardar($_POST['idInstitucion'], $_POST['idEstado'], $_POST['nombre'], $_POST['areaM2'], $_POST['descripcion']);
    header("Location: listar.php?msg=guardado");
    exit;
}

include(__DIR__ . "/vistas/registrar_vista.html");