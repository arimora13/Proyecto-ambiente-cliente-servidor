<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador"]);

$listaRoles   = $conexion->query("SELECT ID_ROL, NOMBRE_ROL FROM ROL")->fetchAll(PDO::FETCH_ASSOC);
$listaEstados = $conexion->query("SELECT ID_ESTADO, NOMBRE_ESTADO FROM ESTADO")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Huertica - Nuevo usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
<?php include("../menu_incluido.php"); ?>

<div class="container mt-2">
    <div class="contenedor-modulo" style="max-width:600px;margin:auto;">
        <h4 class="mb-3">Nuevo usuario</h4>
        <form method="POST" action="../procesos/guardarUsuario.php" id="formUsuario" novalidate>
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Apellido paterno</label>
                <input type="text" class="form-control" id="apellidoPaterno" name="apellidoPaterno" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Apellido materno</label>
                <input type="text" class="form-control" id="apellidoMaterno" name="apellidoMaterno" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Correo electronico</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contrasena</label>
                <input type="password" class="form-control" id="clave" name="clave" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Rol</label>
                <select class="form-select" id="idRol" name="idRol" required>
                    <?php foreach ($listaRoles as $r): ?>
                        <option value="<?php echo $r['ID_ROL']; ?>"><?php echo htmlspecialchars($r['NOMBRE_ROL']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select class="form-select" id="idEstado" name="idEstado" required>
                    <?php foreach ($listaEstados as $e): ?>
                        <option value="<?php echo $e['ID_ESTADO']; ?>"><?php echo htmlspecialchars($e['NOMBRE_ESTADO']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Guardar</button>
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
