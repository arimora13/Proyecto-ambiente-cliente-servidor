<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador","Docente"]);
require_once("../clases/Reporte.php");

$reporteObj = new Reporte($conexion);
$lista = $reporteObj->listar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Huertica - Reportes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
<?php include("../menu_incluido.php"); ?>

<div class="container mt-2">
    <div class="contenedor-modulo">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Reportes de huertas</h4>
            <a href="registrar.php" class="btn btn-success">+ Nuevo reporte</a>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-info">Operacion realizada: <?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>

        <table class="table table-striped">
            <thead>
                <tr><th>ID</th><th>Huerta</th><th>Fecha</th><th>Periodo</th><th>Descripcion</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($lista as $r): ?>
                <tr>
                    <td><?php echo $r['ID_REPORTE']; ?></td>
                    <td><?php echo htmlspecialchars($r['NOMBRE_HUERTA']); ?></td>
                    <td><?php echo $r['FECHA']; ?></td>
                    <td><?php echo htmlspecialchars($r['PERIODO']); ?></td>
                    <td><?php echo htmlspecialchars($r['DESCRIPCION']); ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $r['ID_REPORTE']; ?>" class="btn btn-sm btn-primary">Editar</a>
                        <a href="eliminar.php?id=<?php echo $r['ID_REPORTE']; ?>" class="btn btn-sm btn-danger"
                           onclick="return confirmarEliminar('¿Eliminar este reporte?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="../js/validaciones.js"></script>
</body>
</html>
