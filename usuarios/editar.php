<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);
require_once("../clases/Usuario.php");

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

$usuarioObj = new Usuario($conexion);
$datos = $usuarioObj->obtenerPorId($_GET['id']);

if (!$datos) {
    header("Location: listar.php");
    exit;
}

$listaRoles   = $conexion->query("SELECT ID_ROL, NOMBRE_ROL FROM ROL")->fetchAll(PDO::FETCH_ASSOC);
$listaEstados = $conexion->query("SELECT ID_ESTADO, NOMBRE_ESTADO FROM ESTADO")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Huertica - Editar usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
<?php include("../menu_incluido.php"); ?>

<div class="container mt-2">
    <div class="contenedor-modulo" style="max-width:600px;margin:auto;">
        <h4 class="mb-3">Editar usuario</h4>
        <form method="POST" action="../procesos/editarUsuario.php" id="formUsuario" novalidate>
            <input type="hidden" name="id" value="<?php echo $datos['ID_USUARIO']; ?>">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($datos['NOMBRE']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Apellido paterno</label>
                <input type="text" class="form-control" id="apellidoPaterno" name="apellidoPaterno" value="<?php echo htmlspecialchars($datos['APELLIDO_PATERNO']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Apellido materno</label>
                <input type="text" class="form-control" id="apellidoMaterno" name="apellidoMaterno" value="<?php echo htmlspecialchars($datos['APELLIDO_MATERNO']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Correo electronico</label>
                <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($datos['CORREO']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nueva contrasena (dejar en blanco para no cambiarla)</label>
                <input type="password" class="form-control" id="clave" name="clave">
            </div>
            <div class="mb-3">
                <label class="form-label">Rol</label>
                <select class="form-select" id="idRol" name="idRol" required>
                    <?php foreach ($listaRoles as $r): ?>
                        <option value="<?php echo $r['ID_ROL']; ?>" <?php echo ($r['ID_ROL'] == $datos['ID_ROL']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($r['NOMBRE_ROL']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select class="form-select" id="idEstado" name="idEstado" required>
                    <?php foreach ($listaEstados as $e): ?>
                        <option value="<?php echo $e['ID_ESTADO']; ?>" <?php echo ($e['ID_ESTADO'] == $datos['ID_ESTADO']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($e['NOMBRE_ESTADO']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<script src="../js/validaciones.js"></script>
<script>
document.getElementById('formUsuario').addEventListener('submit', function(e){
    if (!validarFormularioUsuario()) e.preventDefault();
});
</script>
</body>
</html>
