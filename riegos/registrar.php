<?php
require_once("../conexion.php");
validarSesion();
require_once("../clases/Riego.php");

$riegoObj = new Riego($conexion);
$listaCultivos = $conexion->query("SELECT ID_CULTIVO FROM CULTIVO")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $riegoObj->guardar($_POST['idCultivo'], $_SESSION['id_usuario'], $_POST['fecha'], $_POST['descripcion']);
    header("Location: listar.php?msg=guardado");
    exit;
}

include(__DIR__ . "/vistas/registrar_vista.html");
