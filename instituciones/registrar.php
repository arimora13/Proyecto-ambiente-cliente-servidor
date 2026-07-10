<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);
require_once("../clases/Institucion.php");

$institucionObj = new Institucion($conexion);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $institucionObj->guardar($_POST['idProvincia'], $_POST['idCanton'], $_POST['idDistrito'], $_POST['otrasSenas'], $_POST['nombre'], $_POST['telefono']);
    header("Location: listar.php?msg=registrado");
    exit;
}

$listaProvincias = $institucionObj->listarProvincias();
$listaCantones   = $institucionObj->listarCantones();
$listaDistritos  = $institucionObj->listarDistritos();

include(__DIR__ . "/vistas/registrar_vista.html");