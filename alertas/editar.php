<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador","Docente"]);
require_once("../clases/Alerta.php");

$alertaObj = new Alerta($conexion);

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $alertaObj->editar($_POST['id'], $_POST['idEstado'], $_POST['descripcion']);
    header("Location: listar.php?msg=actualizada");
    exit;
}

$datos = $alertaObj->obtenerPorId($_GET['id']);
if (!$datos) {
    header("Location: listar.php");
    exit;
}
$listaEstados = $conexion->query("SELECT ID_ESTADO, NOMBRE_ESTADO FROM ESTADO")->fetchAll(PDO::FETCH_ASSOC);

include(__DIR__ . "/vistas/editar_vista.html");
