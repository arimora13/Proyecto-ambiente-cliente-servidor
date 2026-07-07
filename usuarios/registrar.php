<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);

$listaRoles   = $conexion->query("SELECT ID_ROL, NOMBRE_ROL FROM ROL")->fetchAll(PDO::FETCH_ASSOC);
$listaEstados = $conexion->query("SELECT ID_ESTADO, NOMBRE_ESTADO FROM ESTADO")->fetchAll(PDO::FETCH_ASSOC);

include(__DIR__ . "/vistas/registrar_vista.html");
