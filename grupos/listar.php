<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);
require_once("../clases/GrupoEstudiantil.php");

$grupoObj = new GrupoEstudiantil($conexion);
$listaGrupos = $grupoObj->listar();

include(__DIR__ . "/vistas/listar_vista.html");