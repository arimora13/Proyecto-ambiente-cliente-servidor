<?php
// menu_incluido.php
// Barra de navegacion que se incluye en todas las pantallas internas (listar/registrar/editar de cada modulo).
// Requiere que ya se haya incluido conexion.php y llamado validarSesion() antes.

$rolActual = $_SESSION['nombre_rol'];

$menuBase = [
    ["texto" => "Inicio",           "url" => "/menu.php",                   "roles" => ["Administrador","Docente","Estudiante"]],
    ["texto" => "Cultivos",         "url" => "/cultivos/listar.php",        "roles" => ["Administrador","Docente","Estudiante"]],
    ["texto" => "Riegos",           "url" => "/riegos/listar.php",          "roles" => ["Administrador","Docente","Estudiante"]],
    ["texto" => "Fertilizaciones",  "url" => "/fertilizaciones/listar.php", "roles" => ["Administrador","Docente","Estudiante"]],
    ["texto" => "Plagas",           "url" => "/plagas/listar.php",          "roles" => ["Administrador","Docente","Estudiante"]],
    ["texto" => "Alertas",          "url" => "/alertas/listar.php",         "roles" => ["Administrador","Docente"]],
    ["texto" => "Reportes",         "url" => "/reportes/listar.php",        "roles" => ["Administrador","Docente"]],
    ["texto" => "Usuarios",         "url" => "/usuarios/listar.php",        "roles" => ["Administrador"]],
];
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-success mb-3">
    <div class="container-fluid">
        <a class="navbar-brand" href="/menu.php">🌱 Huertica</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <?php foreach ($menuBase as $item): ?>
                    <?php if (in_array($rolActual, $item['roles'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $item['url']; ?>"><?php echo $item['texto']; ?></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <span class="navbar-text text-white me-3">
                <?php echo htmlspecialchars($_SESSION['nombre']); ?> (<?php echo htmlspecialchars($rolActual); ?>)
            </span>
            <a href="/logout.php" class="btn btn-outline-light btn-sm">Salir</a>
        </div>
    </div>
</nav>
