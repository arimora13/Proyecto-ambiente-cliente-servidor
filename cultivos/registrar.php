<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador","Docente"]);
require_once("../clases/Cultivo.php");

$cultivoObj = new Cultivo($conexion);
$listaHuertas = $cultivoObj->listarHuertas();
$listaTipos   = $cultivoObj->listarTiposCultivo();
$listaGrupos  = $cultivoObj->listarGrupos();
$listaEstados = $cultivoObj->listarEstados();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cultivoObj->guardar(
        $_POST['idHuerta'],
        $_POST['idTipoCultivo'],
        $_POST['idGrupo'],
        $_POST['idEstado'],
        $_POST['fechaSiembra'],
        $_POST['cantidad']
    );
    header("Location: listar.php?msg=guardado");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Huertica - Nuevo cultivo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
<?php include("../menu_incluido.php"); ?>

<div class="container mt-2">
    <div class="contenedor-modulo" style="max-width:600px;margin:auto;">
        <h4 class="mb-3">Nuevo cultivo</h4>
        <form method="POST" action="registrar.php" id="formCultivo" novalidate>
            <div class="mb-3">
                <label class="form-label">Huerta</label>
                <select class="form-select" id="idHuerta" name="idHuerta" required>
                    <?php foreach ($listaHuertas as $h): ?>
                        <option value="<?php echo $h['ID_HUERTA']; ?>"><?php echo htmlspecialchars($h['NOMBRE']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Tipo de cultivo</label>
                <select class="form-select" id="idTipoCultivo" name="idTipoCultivo" required>
                    <?php foreach ($listaTipos as $t): ?>
                        <option value="<?php echo $t['ID_TIPO_CULTIVO']; ?>"><?php echo htmlspecialchars($t['NOMBRE']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Grupo estudiantil</label>
                <select class="form-select" id="idGrupo" name="idGrupo" required>
                    <?php foreach ($listaGrupos as $g): ?>
                        <option value="<?php echo $g['ID_GRUPO']; ?>"><?php echo htmlspecialchars($g['NOMBRE']); ?></option>
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
            <div class="mb-3">
                <label class="form-label">Fecha de siembra</label>
                <input type="date" class="form-control" id="fechaSiembra" name="fechaSiembra" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Cantidad</label>
                <input type="number" min="1" class="form-control" id="cantidad" name="cantidad" required>
            </div>
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<script src="../js/validaciones.js"></script>
<script>
document.getElementById('formCultivo').addEventListener('submit', function(e){
    if (!validarFormularioGeneral(["idHuerta","idTipoCultivo","idGrupo","idEstado","fechaSiembra","cantidad"])) e.preventDefault();
});
</script>
</body>
</html>
