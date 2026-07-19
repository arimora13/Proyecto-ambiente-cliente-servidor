<?php
require_once("../conexion.php");
//validacion de roles y sesion
validarSesion(); 
validarRol(["Administrador","Docente"]);

//carga la clase de alertas
require_once("../clases/Alerta.php");

//si el id de la alerta existe
if (isset($_GET['id'])) {
    $alertaObj = new Alerta($conexion);
    $alertaObj->eliminar($_GET['id']);
    //si esta existe se elimina
}

//se muestra un mensaje de eliminacion
header("Location: listar.php?msg=eliminada");
exit;
?>
