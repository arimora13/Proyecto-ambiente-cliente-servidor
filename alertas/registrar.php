<?php
require_once("../conexion.php"); //conexion
validarSesion(); //verificar sesion
validarRol(["Administrador","Docente"]); //roles

//carga la clase y crea el objeto
require_once("../clases/Alerta.php"); 
$alertaObj = new Alerta($conexion);

//obtiene la lista de los cultivos de la bd
$listaCultivos = $conexion->query("SELECT ID_CULTIVO FROM CULTIVO")->
fetchAll(PDO::FETCH_ASSOC);

//Obtiene los tipos de actividades de la bd
$listaTipos    = $conexion->query("SELECT ID_TIPO_ACTIVIDAD, NOMBRE_ACTIVIDAD 
FROM TIPO_ACTIVIDAD")->fetchAll(PDO::FETCH_ASSOC);

//obtiene los estados para la alerta
$listaEstados  = $conexion->query("SELECT ID_ESTADO, NOMBRE_ESTADO 
FROM ESTADO")->fetchAll(PDO::FETCH_ASSOC);

//si el formulario ya fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $alertaObj->guardar(
        $_POST['idCultivo'], 
        $_SESSION['id_usuario'], 
        $_POST['idTipoActividad'], 
        $_POST['idEstado'], 
        $_POST['descripcion']
        );
        //guarda la nueva alertar

        //muestra un mensaje de exito y redirige al listado
    header("Location: listar.php?msg=guardado");
    exit;
} 

//carga la vista del formulario registrado
include(__DIR__ . "/vistas/registrar_vista.html");