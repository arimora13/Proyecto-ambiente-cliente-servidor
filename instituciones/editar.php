<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);
require_once("../clases/Institucion.php");

$institucionObj = new Institucion($conexion);

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $institucionObj->editar($_POST['id'], $_POST['idDireccion'], $_POST['idProvincia'], $_POST['idCanton'], $_POST['idDistrito'], $_POST['otrasSenas'], $_POST['nombre'], $_POST['telefono']);
    header("Location: listar.php?msg=editado");
    exit;
}

$datos = $institucionObj->obtenerPorId($_GET['id']);
if (!$datos) {
    header("Location: listar.php");
    exit;
}

$listaProvincias = $institucionObj->listarProvincias();
$listaCantones   = $institucionObj->listarCantones();
$listaDistritos  = $institucionObj->listarDistritos();

include(__DIR__ . "/vistas/editar_vista.html");