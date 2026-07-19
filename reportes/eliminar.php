<?php

require_once("../conexion.php"); //BD
validarSesion(); //comprobar la sesion
validarRol(["Administrador","Docente"]); //validar el rol
require_once("../clases/Reporte.php");

//en caso de encontrar el id ingresado 
if(isset($_GET['id'])){
    $reporteObj = new Reporte($conexion);
    $reporteObj->eliminar($_GET['id']); //se llama al metodo de eliminar
}

//se muestra mensaje exitoso
header("Location: listar.php?msg=eliminado");
exit;

?>
