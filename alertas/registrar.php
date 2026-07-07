<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador","Docente"]);
require_once("../clases/Alerta.php");

$alertaObj = new Alerta($conexion);
$listaCultivos = $conexion->query("SELECT ID_CULTIVO FROM CULTIVO")->fetchAll(PDO::FETCH_ASSOC);
$listaTipos    = $conexion->query("SELECT ID_TIPO_ACTIVIDAD, NOMBRE_ACTIVIDAD FROM TIPO_ACTIVIDAD")->fetchAll(PDO::FETCH_ASSOC);
$listaEstados  = $conexion->query("SELECT ID_ESTADO, NOMBRE_ESTADO FROM ESTADO")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $alertaObj->guardar($_POST['idCultivo'], $_SESSION['id_usuario'], $_POST['idTipoActividad'], $_POST['idEstado'], $_POST['descripcion']);
    header("Location: listar.php?msg=guardado");
    exit;
}

include(__DIR__ . "/vistas/registrar_vista.html");
