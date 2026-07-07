<?php
require_once("../conexion.php");
validarSesion();
require_once("../clases/Plaga.php");

$plagaObj = new Plaga($conexion);
$lista = $plagaObj->listar();

include(__DIR__ . "/vistas/listar_vista.html");
