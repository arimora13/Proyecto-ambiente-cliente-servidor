<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador","Docente"]);
require_once("../clases/Cultivo.php");

$cultivoObj = new Cultivo($conexion);
$listaHuertas = $cultivoObj->listarHuertas();
$listaTipos   = $cultivoObj->listarTiposCultivo();
$listaGrupos  = $cultivoObj->listarGrupos();
$listaEstados = $cultivoObj->listarEstados();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cultivoObj->guardar(
        $_POST['idHuerta'],
        $_POST['idTipoCultivo'],
        $_POST['idGrupo'],
        $_POST['idEstado'],
        $_POST['fechaSiembra'],
        $_POST['cantidad']
    );
    header("Location: listar.php?msg=guardado");
    exit;
}

include(__DIR__ . "/vistas/registrar_vista.html");
