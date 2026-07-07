<?php
require_once("conexion.php");
validarSesion();
require_once("clases/Alerta.php");

$rol = $_SESSION['nombre_rol'];

// Contadores simples para el dashboard
$totalUsuarios  = $conexion->query("SELECT COUNT(*) AS T FROM USUARIO")->fetch(PDO::FETCH_ASSOC)['T'];
$totalCultivos  = $conexion->query("SELECT COUNT(*) AS T FROM CULTIVO")->fetch(PDO::FETCH_ASSOC)['T'];
$totalHuertas   = $conexion->query("SELECT COUNT(*) AS T FROM HUERTA")->fetch(PDO::FETCH_ASSOC)['T'];

$alerta = new Alerta($conexion);
$alertasPendientes = $alerta->contarPendientes();

// Definicion de menu por rol
$menuBase = [
    ["texto" => "Cultivos",         "url" => "cultivos/listar.php",         "roles" => ["Administrador","Docente","Estudiante"]],
    ["texto" => "Riegos",           "url" => "riegos/listar.php",           "roles" => ["Administrador","Docente","Estudiante"]],
    ["texto" => "Fertilizaciones",  "url" => "fertilizaciones/listar.php",  "roles" => ["Administrador","Docente","Estudiante"]],
    ["texto" => "Plagas",           "url" => "plagas/listar.php",           "roles" => ["Administrador","Docente","Estudiante"]],
    ["texto" => "Alertas",          "url" => "alertas/listar.php",          "roles" => ["Administrador","Docente"]],
    ["texto" => "Reportes",         "url" => "reportes/listar.php",         "roles" => ["Administrador","Docente"]],
    ["texto" => "Usuarios",         "url" => "usuarios/listar.php",         "roles" => ["Administrador"]],
];

include(__DIR__ . "/vistas/menu_vista.html");
