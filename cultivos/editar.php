<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador","Docente"]);
require_once("../clases/Cultivo.php");

$cultivoObj = new Cultivo($conexion);

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cultivoObj->editar(
        $_POST['id'],
        $_POST['idHuerta'],
        $_POST['idTipoCultivo'],
        $_POST['idGrupo'],
        $_POST['idEstado'],
        $_POST['fechaSiembra'],
        $_POST['cantidad']
    );
    header("Location: listar.php?msg=editado");
    exit;
}

$datos = $cultivoObj->obtenerPorId($_GET['id']);
if (!$datos) {
    header("Location: listar.php");
    exit;
}

$listaHuertas = $cultivoObj->listarHuertas();
$listaTipos   = $cultivoObj->listarTiposCultivo();
$listaGrupos  = $cultivoObj->listarGrupos();
$listaEstados = $cultivoObj->listarEstados();

include(__DIR__ . "/vistas/editar_vista.html");
