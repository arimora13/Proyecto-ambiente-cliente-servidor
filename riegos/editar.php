<?php
require_once("../conexion.php");
validarSesion();
require_once("../clases/Riego.php");

$riegoObj = new Riego($conexion);

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $riegoObj->editar($_POST['id'], $_POST['idCultivo'], $_POST['fecha'], $_POST['descripcion']);
    header("Location: listar.php?msg=editado");
    exit;
}

$datos = $riegoObj->obtenerPorId($_GET['id']);
if (!$datos) {
    header("Location: listar.php");
    exit;
}
$listaCultivos = $conexion->query("SELECT ID_CULTIVO FROM CULTIVO")->fetchAll(PDO::FETCH_ASSOC);

include(__DIR__ . "/vistas/editar_vista.html");
