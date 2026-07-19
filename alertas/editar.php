<?php
require_once("../conexion.php");
validarSesion(); //el usuario ya inicio sesion?
validarRol(["Administrador","Docente"]); //ESTA VISTA SOLO ESTA PARA ADMINS Y PROFES
require_once("../clases/Alerta.php");

$alertaObj = new Alerta($conexion);

//inicio de secuencia if para validaciones
if (!isset($_GET['id'])) { //si no existe entra al if
    header("Location: listar.php");
    //regresa al listado
    exit;
}

//si el formulario se envio
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $alertaObj->editar($_POST['id'], $_POST['idEstado'], $_POST['descripcion']);
    //actualiza la info y redirige al listado
    header("Location: listar.php?msg=actualizada");
}
//trae los datos de la alerta seleccionada
$datos = $alertaObj->obtenerPorId($_GET['id']);

//si esta no existe
if (!$datos) {
    header("Location: listar.php");
    exit;
}

//obtiene la lisra de estados
$listaEstados = $conexion->query("SELECT ID_ESTADO, NOMBRE_ESTADO FROM ESTADO")->fetchAll(PDO::FETCH_ASSOC);
//para desplegar el menu del formulario

//carga el formulario con los datos obtenidos
include(__DIR__ . "/vistas/editar_vista.html");