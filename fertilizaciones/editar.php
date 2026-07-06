<?php
require_once("../conexion.php");
validarSesion();
require_once("../clases/Fertilizacion.php");

$fertilizacionObj = new Fertilizacion($conexion);

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fertilizacionObj->editar($_POST['id'], $_POST['idCultivo'], $_POST['fecha'], $_POST['descripcion']);
    header("Location: listar.php?msg=editado");
    exit;
}

$datos = $fertilizacionObj->obtenerPorId($_GET['id']);
if (!$datos) {
    header("Location: listar.php");
    exit;
}
$listaCultivos = $conexion->query("SELECT ID_CULTIVO FROM CULTIVO")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Huertica - Editar fertilizacion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
<?php include("../menu_incluido.php"); ?>

<div class="container mt-2">
    <div class="contenedor-modulo" style="max-width:600px;margin:auto;">
        <h4 class="mb-3">Editar fertilizacion</h4>
        <form method="POST" action="editar.php?id=<?php echo $datos['ID_ACTIVIDAD']; ?>" id="formFertilizacion" novalidate>
            <input type="hidden" name="id" value="<?php echo $datos['ID_ACTIVIDAD']; ?>">
            <div class="mb-3">
                <label class="form-label">Cultivo</label>
                <select class="form-select" id="idCultivo" name="idCultivo" required>
                    <?php foreach ($listaCultivos as $c): ?>
                        <option value="<?php echo $c['ID_CULTIVO']; ?>" <?php echo ($c['ID_CULTIVO']==$datos['ID_CULTIVO'])?'selected':''; ?>>Cultivo #<?php echo $c['ID_CULTIVO']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $datos['FECHA_ACTIVIDAD']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripcion</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo htmlspecialchars($datos['DESCRIPCION']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
<script src="../js/validaciones.js"></script>
<script>
document.getElementById('formFertilizacion').addEventListener('submit', function(e){
    if (!validarFormularioGeneral(["idCultivo","fecha","descripcion"])) e.preventDefault();
});
</script>
</body>
</html>
