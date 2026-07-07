<?php
// menu_incluido.php
// Barra de navegacion que se incluye en todas las pantallas internas (listar/registrar/editar de cada modulo).
// Requiere que ya se haya incluido conexion.php y llamado validarSesion() antes.

$rolActual = $_SESSION['nombre_rol'];

$menuBase = [
    ["texto" => "Inicio",           "url" => BASE_URL . "/menu.php",                   "roles" => ["Administrador","Docente","Estudiante"]],
    ["texto" => "Cultivos",         "url" => BASE_URL . "/cultivos/listar.php",        "roles" => ["Administrador","Docente","Estudiante"]],
    ["texto" => "Riegos",           "url" => BASE_URL . "/riegos/listar.php",          "roles" => ["Administrador","Docente","Estudiante"]],
    ["texto" => "Fertilizaciones",  "url" => BASE_URL . "/fertilizaciones/listar.php", "roles" => ["Administrador","Docente","Estudiante"]],
    ["texto" => "Plagas",           "url" => BASE_URL . "/plagas/listar.php",          "roles" => ["Administrador","Docente","Estudiante"]],
    ["texto" => "Alertas",          "url" => BASE_URL . "/alertas/listar.php",         "roles" => ["Administrador","Docente"]],
    ["texto" => "Reportes",         "url" => BASE_URL . "/reportes/listar.php",        "roles" => ["Administrador","Docente"]],
    ["texto" => "Usuarios",         "url" => BASE_URL . "/usuarios/listar.php",        "roles" => ["Administrador"]],
];

include(__DIR__ . "/vistas/menu_incluido_vista.html");
