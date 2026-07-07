<?php
// conexion.php
// Conexion unica a la base de datos MySQL "Huertica" usando PDO.
// Todos los demas archivos incluyen este archivo primero.

session_start();

// Ruta base del proyecto dentro de htdocs, calculada automaticamente
// comparando la carpeta de este archivo contra la raiz del servidor web.
// No hay que tocar esto nunca, sin importar como se llame la carpeta
// del proyecto ni en que subcarpeta de htdocs este.
$raizServidor = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
$raizProyecto = str_replace('\\', '/', __DIR__);
define('BASE_URL', substr($raizProyecto, strlen($raizServidor)));

$servidor   = "localhost";      // Nombre o IP del servidor MySQL
$baseDatos  = "Huertica";
$usuarioBD  = "root";           // Cambiar por el usuario real
$claveBD    = "";               // Cambiar por la clave real

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$baseDatos;charset=utf8mb4", $usuarioBD, $claveBD);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error) {
    die("Error de conexion a la base de datos: " . $error->getMessage());
}

// Funcion para validar que exista sesion activa. Se usa en todas las paginas internas.
function validarSesion() {
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: " . BASE_URL . "/login.php");
        exit;
    }
}

// Funcion para validar que el rol de la sesion tenga permiso de acceso a un modulo.
function validarRol($rolesPermitidos) {
    if (!in_array($_SESSION['nombre_rol'], $rolesPermitidos)) {
        echo "<script>alert('No tiene permisos para acceder a este modulo.'); window.location='" . BASE_URL . "/menu.php';</script>";
        exit;
    }
}
?>
