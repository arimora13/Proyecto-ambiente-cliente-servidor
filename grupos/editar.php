<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);
require_once("../clases/GrupoEstudiantil.php");

$grupoObj = new GrupoEstudiantil($conexion);

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $grupoObj->editar($_POST['id'], $_POST['idDocente'], $_POST['nombre'], $_POST['grado'], $_POST['seccion'], $_POST['anyo']);
    header("Location: listar.php?msg=editado");
    exit;
}

$datos = $grupoObj->obtenerPorId($_GET['id']);
if (!$datos) {
    header("Location: listar.php");
    exit;
}
$listaDocentes = $grupoObj->listarDocentes();

include(__DIR__ . "/vistas/editar_vista.html");