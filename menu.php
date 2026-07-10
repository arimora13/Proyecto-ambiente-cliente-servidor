<?php
require_once("conexion.php");
validarSesion();
require_once("clases/Alerta.php");

$rol = $_SESSION['nombre_rol'];

// Contadores simples para el dashboard
$totalUsuarios  = $conexion->query("SELECT COUNT(*) AS T FROM USUARIO")->fetch(PDO::FETCH_ASSOC)['T'];
$totalCultivos  = $conexion->query("SELECT COUNT(*) AS T FROM CULTIVO")->fetch(PDO::FETCH_ASSOC)['T'];
$totalHuertas   = $conexion->query("SELECT COUNT(*) AS T FROM HUERTA")->fetch(PDO::FETCH_ASSOC)['T'];
$totalGrupos    = $conexion->query("SELECT COUNT(*) AS T FROM GRUPO_ESTUDIANTIL")->fetch(PDO::FETCH_ASSOC)['T'];
$totalEscuelas  = $conexion->query("SELECT COUNT(*) AS T FROM INSTITUCION")->fetch(PDO::FETCH_ASSOC)['T'];

$riegosHoy = $conexion->query("SELECT COUNT(*) AS T FROM ACTIVIDAD A
                               INNER JOIN TIPO_ACTIVIDAD TA ON A.ID_TIPO_ACTIVIDAD = TA.ID_TIPO_ACTIVIDAD
                               WHERE TA.NOMBRE_ACTIVIDAD = 'Riego' AND A.FECHA_ACTIVIDAD = CURDATE()")->fetch(PDO::FETCH_ASSOC)['T'];

$alerta = new Alerta($conexion);
$alertasPendientes = $alerta->contarPendientes();

// Cultivos agrupados por estado (para el grafico de pastel)
$cultivosPorEstado = $conexion->query("SELECT E.NOMBRE_ESTADO, COUNT(*) AS TOTAL
                                       FROM CULTIVO C
                                       LEFT JOIN ESTADO E ON C.ID_ESTADO = E.ID_ESTADO
                                       GROUP BY E.NOMBRE_ESTADO")->fetchAll(PDO::FETCH_ASSOC);

// Actividades registradas por mes, ultimos 6 meses (para el grafico de barras)
$actividadPorMes = $conexion->query("SELECT DATE_FORMAT(FECHA_ACTIVIDAD, '%Y-%m') AS MES, COUNT(*) AS TOTAL
                                     FROM ACTIVIDAD
                                     WHERE FECHA_ACTIVIDAD >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                                     GROUP BY MES
                                     ORDER BY MES ASC")->fetchAll(PDO::FETCH_ASSOC);

// Ultimas 5 actividades registradas (cualquier tipo: riego, fertilizacion, plaga, etc.)
$ultimasActividades = $conexion->query("SELECT A.FECHA_ACTIVIDAD, TA.NOMBRE_ACTIVIDAD, TC.NOMBRE AS NOMBRE_CULTIVO, U.NOMBRE AS RESPONSABLE
                                        FROM ACTIVIDAD A
                                        LEFT JOIN TIPO_ACTIVIDAD TA ON A.ID_TIPO_ACTIVIDAD = TA.ID_TIPO_ACTIVIDAD
                                        LEFT JOIN CULTIVO C ON A.ID_CULTIVO = C.ID_CULTIVO
                                        LEFT JOIN TIPO_CULTIVO TC ON C.ID_TIPO_CULTIVO = TC.ID_TIPO_CULTIVO
                                        LEFT JOIN USUARIO U ON A.ID_USUARIO = U.ID_USUARIO
                                        ORDER BY A.FECHA_ACTIVIDAD DESC
                                        LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// Ultimas 5 alertas registradas
$alertasRecientes = $conexion->query("SELECT AL.DESCRIPCION, E.NOMBRE_ESTADO, TA.NOMBRE_ACTIVIDAD
                                      FROM ALERTA AL
                                      LEFT JOIN ESTADO E ON AL.ID_ESTADO = E.ID_ESTADO
                                      LEFT JOIN TIPO_ACTIVIDAD TA ON AL.ID_TIPO_ACTIVIDAD = TA.ID_TIPO_ACTIVIDAD
                                      ORDER BY AL.ID_ALERTA DESC
                                      LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// Definicion de menu por rol
$menuBase = [
    ["texto" => "Cultivos",         "url" => "cultivos/listar.php",         "roles" => ["Administrador","Docente","Estudiante"]],
    ["texto" => "Riegos",           "url" => "riegos/listar.php",           "roles" => ["Administrador","Docente","Estudiante"]],
    ["texto" => "Fertilizaciones",  "url" => "fertilizaciones/listar.php",  "roles" => ["Administrador","Docente","Estudiante"]],
    ["texto" => "Plagas",           "url" => "plagas/listar.php",           "roles" => ["Administrador","Docente","Estudiante"]],
    ["texto" => "Alertas",          "url" => "alertas/listar.php",          "roles" => ["Administrador","Docente"]],
    ["texto" => "Reportes",         "url" => "reportes/listar.php",         "roles" => ["Administrador","Docente"]],
    ["texto" => "Usuarios",         "url" => "usuarios/listar.php",         "roles" => ["Administrador"]],
    ["texto" => "Huertas",          "url" => "huertas/listar.php",          "roles" => ["Administrador"]],
    ["texto" => "Escuelas",         "url" => "instituciones/listar.php",    "roles" => ["Administrador"]],
    ["texto" => "Grupos",           "url" => "grupos/listar.php",           "roles" => ["Administrador"]],
    ["texto" => "Tipos de cultivo", "url" => "tipos_cultivo/listar.php",    "roles" => ["Administrador","Docente","Estudiante"]],
];

include(__DIR__ . "/vistas/menu_vista.html");