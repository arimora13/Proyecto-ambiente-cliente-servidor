<?php
require_once("../conexion.php");
validarSesion();
require_once("../clases/Cultivo.php");

$cultivoObj = new Cultivo($conexion);
$listaCultivos = $cultivoObj->listar();

$verSeguimiento = null;
$historial = [];
if (isset($_GET['seguimiento'])) {
    $verSeguimiento = $_GET['seguimiento'];
    $historial = $cultivoObj->seguimiento($verSeguimiento);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Huertica - Cultivos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
<?php include("../menu_incluido.php"); ?>

<div class="container mt-2">
    <div class="contenedor-modulo">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Cultivos</h4>
            <a href="registrar.php" class="btn btn-success">+ Nuevo cultivo</a>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-info">Operacion realizada: <?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Huerta</th>
                    <th>Tipo</th>
                    <th>Grupo</th>
                    <th>Estado</th>
                    <th>Fecha siembra</th>
                    <th>Cantidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaCultivos as $c): ?>
                <tr>
                    <td><?php echo $c['ID_CULTIVO']; ?></td>
                    <td><?php echo htmlspecialchars($c['NOMBRE_HUERTA']); ?></td>
                    <td><?php echo htmlspecialchars($c['NOMBRE_TIPO']); ?></td>
                    <td><?php echo htmlspecialchars($c['NOMBRE_GRUPO']); ?></td>
                    <td><?php echo htmlspecialchars($c['NOMBRE_ESTADO']); ?></td>
                    <td><?php echo $c['FECHA_SIEMBRA']; ?></td>
                    <td><?php echo $c['CANTIDAD']; ?></td>
                    <td>
                        <a href="listar.php?seguimiento=<?php echo $c['ID_CULTIVO']; ?>" class="btn btn-sm btn-info">Seguimiento</a>
                        <a href="editar.php?id=<?php echo $c['ID_CULTIVO']; ?>" class="btn btn-sm btn-primary">Editar</a>
                        <a href="eliminar.php?id=<?php echo $c['ID_CULTIVO']; ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirmarEliminar('¿Eliminar este cultivo?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($verSeguimiento !== null): ?>
        <hr>
        <h5>Seguimiento del crecimiento - Cultivo #<?php echo htmlspecialchars($verSeguimiento); ?></h5>
        <table class="table table-bordered">
            <thead>
                <tr><th>Fecha</th><th>Actividad</th><th>Descripcion</th><th>Responsable</th></tr>
            </thead>
            <tbody>
                <?php if (count($historial) == 0): ?>
                    <tr><td colspan="4" class="text-center text-muted">Sin actividades registradas todavia.</td></tr>
                <?php endif; ?>
                <?php foreach ($historial as $h): ?>
                <tr>
                    <td><?php echo $h['FECHA_ACTIVIDAD']; ?></td>
                    <td><?php echo htmlspecialchars($h['NOMBRE_ACTIVIDAD']); ?></td>
                    <td><?php echo htmlspecialchars($h['DESCRIPCION']); ?></td>
                    <td><?php echo htmlspecialchars($h['RESPONSABLE']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<script src="../js/validaciones.js"></script>
</body>
</html>
