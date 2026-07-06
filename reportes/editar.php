<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador","Docente"]);
require_once("../clases/Reporte.php");

$reporteObj = new Reporte($conexion);

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reporteObj->editar($_POST['id'], $_POST['idHuerta'], $_POST['fecha'], $_POST['periodo'], $_POST['descripcion']);
    header("Location: listar.php?msg=editado");
    exit;
}

$datos = $reporteObj->obtenerPorId($_GET['id']);
if (!$datos) {
    header("Location: listar.php");
    exit;
}
$listaHuertas = $reporteObj->listarHuertas();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Huertica - Editar reporte</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
<?php include("../menu_incluido.php"); ?>

<div class="container mt-2">
    <div class="contenedor-modulo" style="max-width:600px;margin:auto;">
        <h4 class="mb-3">Editar reporte</h4>
        <form method="POST" action="editar.php?id=<?php echo $datos['ID_REPORTE']; ?>" id="formReporte" novalidate>
            <input type="hidden" name="id" value="<?php echo $datos['ID_REPORTE']; ?>">
            <div class="mb-3">
                <label class="form-label">Huerta</label>
                <select class="form-select" id="idHuerta" name="idHuerta" required>
                    <?php foreach ($listaHuertas as $h): ?>
                        <option value="<?php echo $h['ID_HUERTA']; ?>" <?php echo ($h['ID_HUERTA']==$datos['ID_HUERTA'])?'selected':''; ?>><?php echo htmlspecialchars($h['NOMBRE']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $datos['FECHA']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Periodo</label>
                <input type="text" class="form-control" id="periodo" name="periodo" value="<?php echo htmlspecialchars($datos['PERIODO']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripcion</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required><?php echo htmlspecialchars($datos['DESCRIPCION']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
<script src="../js/validaciones.js"></script>
<script>
document.getElementById('formReporte').addEventListener('submit', function(e){
    if (!validarFormularioGeneral(["idHuerta","fecha","periodo","descripcion"])) e.preventDefault();
});
</script>
</body>
</html>
