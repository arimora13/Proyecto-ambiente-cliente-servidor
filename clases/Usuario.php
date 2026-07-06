<?php
// clases/Usuario.php
class Usuario {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // CREATE
    public function guardar($idRol, $idEstado, $nombre, $apPaterno, $apMaterno, $clave, $correo) {
        $sql = "INSERT INTO USUARIO (ID_ROL, ID_ESTADO, NOMBRE, APELLIDO_PATERNO, APELLIDO_MATERNO, CONTRASENA, CORREO)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $claveEncriptada = md5($clave); // encriptado simple, nivel didactico
        return $stmt->execute([$idRol, $idEstado, $nombre, $apPaterno, $apMaterno, $claveEncriptada, $correo]);
    }

    // UPDATE
    public function editar($id, $idRol, $idEstado, $nombre, $apPaterno, $apMaterno, $correo) {
        $sql = "UPDATE USUARIO
                SET ID_ROL = ?, ID_ESTADO = ?, NOMBRE = ?, APELLIDO_PATERNO = ?, APELLIDO_MATERNO = ?, CORREO = ?
                WHERE ID_USUARIO = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$idRol, $idEstado, $nombre, $apPaterno, $apMaterno, $correo, $id]);
    }

    // UPDATE de clave (opcional, separado)
    public function cambiarClave($id, $claveNueva) {
        $sql = "UPDATE USUARIO SET CONTRASENA = ? WHERE ID_USUARIO = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([md5($claveNueva), $id]);
    }

    // DELETE
    public function eliminar($id) {
        $sql = "DELETE FROM USUARIO WHERE ID_USUARIO = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    // SELECT todos
    public function listar() {
        $sql = "SELECT U.ID_USUARIO, U.NOMBRE, U.APELLIDO_PATERNO, U.APELLIDO_MATERNO, U.CORREO,
                       R.NOMBRE_ROL, E.NOMBRE_ESTADO, U.ID_ROL, U.ID_ESTADO
                FROM USUARIO U
                LEFT JOIN ROL R ON U.ID_ROL = R.ID_ROL
                LEFT JOIN ESTADO E ON U.ID_ESTADO = E.ID_ESTADO
                ORDER BY U.ID_USUARIO DESC";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // SELECT por id
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM USUARIO WHERE ID_USUARIO = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Validar login (correo + clave) -> retorna el registro del usuario o false
    public function validarLogin($correo, $clave) {
        $sql = "SELECT U.ID_USUARIO, U.NOMBRE, U.CORREO, R.NOMBRE_ROL, E.NOMBRE_ESTADO
                FROM USUARIO U
                INNER JOIN ROL R ON U.ID_ROL = R.ID_ROL
                INNER JOIN ESTADO E ON U.ID_ESTADO = E.ID_ESTADO
                WHERE U.CORREO = ? AND U.CONTRASENA = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$correo, md5($clave)]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado : false;
    }

    // Lista simple para combos (select de docentes, por ejemplo)
    public function listarPorRol($nombreRol) {
        $sql = "SELECT U.ID_USUARIO, U.NOMBRE, U.APELLIDO_PATERNO
                FROM USUARIO U INNER JOIN ROL R ON U.ID_ROL = R.ID_ROL
                WHERE R.NOMBRE_ROL = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$nombreRol]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
