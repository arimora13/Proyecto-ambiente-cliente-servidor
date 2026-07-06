<?php
require_once("../conexion.php");
validarSesion();
require_once("../clases/Plaga.php");

$plagaObj = new Plaga($conexion);
$lista = $plagaObj->listar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Huertica - Plagas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
<?php include("../menu_incluido.php"); ?>

<div class="container mt-2">
    <div class="contenedor-modulo">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Registro de plagas</h4>
            <a href="registrar.php" class="btn btn-success">+ Nuevo registro de plaga</a>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-info">Operacion realizada: <?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>

        <table class="table table-striped">
            <thead>
                <tr><th>ID</th><th>Cultivo</th><th>Fecha</th><th>Descripcion</th><th>Responsable</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($lista as $r): ?>
                <tr>
                    <td><?php echo $r['ID_ACTIVIDAD']; ?></td>
                    <td>#<?php echo $r['ID_CULTIVO']; ?> - <?php echo htmlspecialchars($r['NOMBRE_CULTIVO']); ?></td>
                    <td><?php echo $r['FECHA_ACTIVIDAD']; ?></td>
                    <td><?php echo htmlspecialchars($r['DESCRIPCION']); ?></td>
                    <td><?php echo htmlspecialchars($r['RESPONSABLE']); ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $r['ID_ACTIVIDAD']; ?>" class="btn btn-sm btn-primary">Editar</a>
                        <a href="eliminar.php?id=<?php echo $r['ID_ACTIVIDAD']; ?>" class="btn btn-sm btn-danger"
                           onclick="return confirmarEliminar('¿Eliminar este plaga?')">Eliminar</a>
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
