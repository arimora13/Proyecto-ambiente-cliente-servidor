<?php
require_once("../conexion.php");
validarSesion(); //el usuario ya inicio sesion?
validarRol(["Administrador","Docente"]); //ESTA VISTA SOLO ESTA PARA ADMINS Y PROFES
require_once("../clases/Alerta.php");

$alertaObj = new Alerta($conexion);

//inicio de secuencia if para validaciones
if (!isset($_GET['id']) || empty($_GET['id'])) { //si no existe entra al if
    header("Location: listar.php");
    //regresa al listado
    exit;
}

//si el formulario se envio
$idAlerta = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idEstado = $_POST['idEstado'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $idForm = $_POST['id'] ?? $idAlerta;
    
    $alertaObj->editar($idForm, $idEstado, $descripcion);

    //actualiza la info y redirige al listado
    header("Location: listar.php?msg=actualizada");
    exit;
}
//trae los datos de la alerta seleccionada
$datos = $alertaObj->obtenerPorId($idAlerta);

//si esta no existe
if (!$datos) {
    header("Location: listar.php");
    exit;
}

//obtiene la lisra de estados
$estados = $conexion->query("SELECT ID_ESTADO, NOMBRE_ESTADO FROM ESTADO")->fetchAll(PDO::FETCH_ASSOC);
//para desplegar el menu del formulario

//carga el formulario con los datos obtenidos
include(__DIR__ . "/vistas/editar_vista.html");