<?php
//inicio del php

require_once("../conexion.php");//carga la conexion con bd
validarSesion(); //verifica la sesion iniciada
validarRol(["Administrador","Docente"]); //verifica el rol
require_once("../clases/Alerta.php"); // carga la clase de alerta

$alertaObj = new Alerta($conexion); //crea un objeto
$lista = $alertaObj->listar(); //lista el objeto


include(__DIR__ . "/vistas/listar_vista.html"); //carga la vista de la lista
