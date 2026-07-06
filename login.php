<?php
require_once("conexion.php");
require_once("clases/Usuario.php");

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = trim($_POST['correo']);
    $clave  = trim($_POST['clave']);

    $usuario = new Usuario($conexion);
    $datos = $usuario->validarLogin($correo, $clave);

    if ($datos) {
        $_SESSION['id_usuario']  = $datos['ID_USUARIO'];
        $_SESSION['nombre']      = $datos['NOMBRE'];
        $_SESSION['nombre_rol']  = $datos['NOMBRE_ROL'];
        header("Location: menu.php");
        exit;
    } else {
        $error = "Correo o contrasena incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Huertica - Iniciar sesion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="fondo-login">
    <div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
        <div class="card shadow p-4 tarjeta-login">
            <h3 class="text-center mb-3 text-success">🌱 Huertica</h3>
            <p class="text-center text-muted">Inicia sesion para continuar</p>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php" id="formLogin" novalidate>
                <div class="mb-3">
                    <label class="form-label">Correo electronico</label>
                    <input type="email" class="form-control" name="correo" id="correo" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contrasena</label>
                    <input type="password" class="form-control" name="clave" id="clave" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Ingresar</button>
            </form>
        </div>
    </div>
    <script src="js/validaciones.js"></script>
    <script>
        document.getElementById('formLogin').addEventListener('submit', function(e){
            if (!validarLogin()) e.preventDefault();
        });
    </script>
</body>
</html>
