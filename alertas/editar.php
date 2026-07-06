<?php
require_once("../conexion.php");
validarSesion();
validarRol(["Administrador","Docente"]);
require_once("../clases/Alerta.php");

$alertaObj = new Alerta($conexion);

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $alertaObj->editar($_POST['id'], $_POST['idEstado'], $_POST['descripcion']);
    header("Location: listar.php?msg=actualizada");
    exit;
}

$datos = $alertaObj->obtenerPorId($_GET['id']);
if (!$datos) {
    header("Location: listar.php");
    exit;
}
$listaEstados = $conexion->query("SELECT ID_ESTADO, NOMBRE_ESTADO FROM ESTADO")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Huertica - Atender alerta</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
<?php include("../menu_incluido.php"); ?>

<div class="container mt-2">
    <div class="contenedor-modulo" style="max-width:600px;margin:auto;">
        <h4 class="mb-3">Atender alerta #<?php echo $datos['ID_ALERTA']; ?></h4>
        <form method="POST" action="editar.php?id=<?php echo $datos['ID_ALERTA']; ?>" id="formAlerta" novalidate>
            <input type="hidden" name="id" value="<?php echo $datos['ID_ALERTA']; ?>">
            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select class="form-select" id="idEstado" name="idEstado" required>
                    <?php foreach ($listaEstados as $e): ?>
                        <option value="<?php echo $e['ID_ESTADO']; ?>" <?php echo ($e['ID_ESTADO']==$datos['ID_ESTADO'])?'selected':''; ?>><?php echo htmlspecialchars($e['NOMBRE_ESTADO']); ?></option>
                    <?php endforeach; ?>
                </select>
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
document.getElementById('formAlerta').addEventListener('submit', function(e){
    if (!validarFormularioGeneral(["idEstado","descripcion"])) e.preventDefault();
});
</script>
</body>
</html>
