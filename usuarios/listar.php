<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);
require_once("../clases/Usuario.php");

$usuario = new Usuario($conexion);
$listaUsuarios = $usuario->listar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Huertica - Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
<?php include("../menu_incluido.php"); ?>

<div class="container mt-4">
    <div class="contenedor-modulo">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Usuarios</h4>
            <a href="registrar.php" class="btn btn-success">+ Nuevo usuario</a>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-info">Operacion realizada: <?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaUsuarios as $u): ?>
                <tr>
                    <td><?php echo $u['ID_USUARIO']; ?></td>
                    <td><?php echo htmlspecialchars($u['NOMBRE']); ?></td>
                    <td><?php echo htmlspecialchars($u['APELLIDO_PATERNO'] . " " . $u['APELLIDO_MATERNO']); ?></td>
                    <td><?php echo htmlspecialchars($u['CORREO']); ?></td>
                    <td><?php echo htmlspecialchars($u['NOMBRE_ROL']); ?></td>
                    <td><?php echo htmlspecialchars($u['NOMBRE_ESTADO']); ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $u['ID_USUARIO']; ?>" class="btn btn-sm btn-primary">Editar</a>
                        <a href="../procesos/eliminarUsuario.php?id=<?php echo $u['ID_USUARIO']; ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirmarEliminar('¿Eliminar este usuario?')">Eliminar</a>
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
