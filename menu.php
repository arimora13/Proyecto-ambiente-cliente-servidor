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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Huertica - Menu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container-fluid">
        <a class="navbar-brand" href="menu.php">🌱 Huertica</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <?php foreach ($menuBase as $item): ?>
                    <?php if (in_array($rol, $item['roles'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $item['url']; ?>"><?php echo $item['texto']; ?></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <span class="navbar-text text-white me-3">
                <?php echo htmlspecialchars($_SESSION['nombre']); ?> (<?php echo htmlspecialchars($rol); ?>)
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Salir</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h4 class="mb-3">Panel general</h4>
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card text-bg-success p-3 text-center">
                <h2><?php echo $totalUsuarios; ?></h2>
                <p class="mb-0">Usuarios</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-primary p-3 text-center">
                <h2><?php echo $totalCultivos; ?></h2>
                <p class="mb-0">Cultivos</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-info p-3 text-center">
                <h2><?php echo $totalHuertas; ?></h2>
                <p class="mb-0">Huertas</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-warning p-3 text-center">
                <h2><?php echo $alertasPendientes; ?></h2>
                <p class="mb-0">Alertas pendientes</p>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <p class="text-muted">Selecciona una opcion del menu superior para gestionar la informacion.</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
