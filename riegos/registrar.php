<?php
require_once("../conexion.php");
validarSesion();
require_once("../clases/Riego.php");

$riegoObj = new Riego($conexion);


$listaCultivos = $conexion->query("SELECT C.ID_CULTIVO, TC.NOMBRE AS NOMBRE_TIPO, H.NOMBRE AS NOMBRE_HUERTA
                                    FROM CULTIVO C
                                    LEFT JOIN TIPO_CULTIVO TC ON C.ID_TIPO_CULTIVO = TC.ID_TIPO_CULTIVO
                                    LEFT JOIN HUERTA H ON C.ID_HUERTA = H.ID_HUERTA
                                    ORDER BY C.ID_CULTIVO DESC")->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      $riegoObj ->guardar($_POST['idCultivo'], 
      $_SESSION['id_usuario'], 
      $_POST['fecha'], 
      $_POST['descripcion']);

       header ("Location: listar.php?msg=registrado");

       exit; 

}

include(__DIR__ . "/vistas/registrar_vista.html");
