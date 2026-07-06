<?php
// conexion.php
// Conexion unica a la base de datos SQL Server "Huertica" usando PDO + driver sqlsrv.
// Todos los demas archivos incluyen este archivo primero.

session_start();

$servidor   = "localhost";      // Nombre o IP del servidor SQL Server
$baseDatos  = "Huertica";
$usuarioBD  = "sa";             // Cambiar por el usuario real
$claveBD    = "TU_PASSWORD";    // Cambiar por la clave real

try {
    $conexion = new PDO("sqlsrv:Server=$servidor;Database=$baseDatos", $usuarioBD, $claveBD);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error) {
    die("Error de conexion a la base de datos: " . $error->getMessage());
}

// Funcion para validar que exista sesion activa. Se usa en todas las paginas internas.
function validarSesion() {
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: /login.php");
        exit;
    }
}

// Funcion para validar que el rol de la sesion tenga permiso de acceso a un modulo.
function validarRol($rolesPermitidos) {
    if (!in_array($_SESSION['nombre_rol'], $rolesPermitidos)) {
        echo "<script>alert('No tiene permisos para acceder a este modulo.'); window.location='/menu.php';</script>";
        exit;
    }
}
?>
