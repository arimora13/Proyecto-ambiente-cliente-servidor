<?php
require_once("../conexion.php");
validarSesion();
require_once("../clases/Plaga.php");

$plagaObj = new Plaga($conexion);

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plagaObj->editar($_POST['id'], $_POST['idCultivo'], $_POST['fecha'], $_POST['descripcion']);
    header("Location: listar.php?msg=editado");
    exit;
}

$datos = $plagaObj->obtenerPorId($_GET['id']);
if (!$datos) {
    header("Location: listar.php");
    exit;
}
$listaCultivos = $conexion->query("SELECT C.ID_CULTIVO, TC.NOMBRE AS NOMBRE_TIPO, H.NOMBRE AS NOMBRE_HUERTA
                                    FROM CULTIVO C
                                    LEFT JOIN TIPO_CULTIVO TC ON C.ID_TIPO_CULTIVO = TC.ID_TIPO_CULTIVO
                                    LEFT JOIN HUERTA H ON C.ID_HUERTA = H.ID_HUERTA
                                    ORDER BY C.ID_CULTIVO DESC")->fetchAll(PDO::FETCH_ASSOC);
                                    
include(__DIR__ . "/vistas/editar_vista.html");
