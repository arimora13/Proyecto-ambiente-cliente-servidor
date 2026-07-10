<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);
require_once("../clases/GrupoEstudiantil.php");

$grupoObj = new GrupoEstudiantil($conexion);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $grupoObj->guardar($_POST['idDocente'], $_POST['nombre'], $_POST['grado'], $_POST['seccion'], $_POST['año']);
    header("Location: listar.php?msg=registrado");
    exit;
}

$listaDocentes = $grupoObj->listarDocentes();

include(__DIR__ . "/vistas/registrar_vista.html");