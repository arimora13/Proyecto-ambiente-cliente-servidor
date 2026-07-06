<?php
require_once("../conexion.php");
validarSesion();
require_once("../clases/Plaga.php");

$plagaObj = new Plaga($conexion);
$listaCultivos = $conexion->query("SELECT ID_CULTIVO FROM CULTIVO")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plagaObj->guardar($_POST['idCultivo'], $_SESSION['id_usuario'], $_POST['fecha'], $_POST['descripcion']);
    header("Location: listar.php?msg=guardado");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Huertica - Nuevo registro de plaga</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
<?php include("../menu_incluido.php"); ?>

<div class="container mt-2">
    <div class="contenedor-modulo" style="max-width:600px;margin:auto;">
        <h4 class="mb-3">Registrar plaga</h4>
        <form method="POST" action="registrar.php" id="formPlaga" novalidate>
            <div class="mb-3">
                <label class="form-label">Cultivo</label>
                <select class="form-select" id="idCultivo" name="idCultivo" required>
                    <?php foreach ($listaCultivos as $c): ?>
                        <option value="<?php echo $c['ID_CULTIVO']; ?>">Cultivo #<?php echo $c['ID_CULTIVO']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fecha" name="fecha" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripcion</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
<script src="../js/validaciones.js"></script>
<script>
document.getElementById('formPlaga').addEventListener('submit', function(e){
    if (!validarFormularioGeneral(["idCultivo","fecha","descripcion"])) e.preventDefault();
});
</script>
</body>
</html>
