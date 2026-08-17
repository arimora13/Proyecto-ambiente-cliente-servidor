<?php
class Usuario {
    private PDO $conexion;

    public function __construct(PDO $conexion) {
        $this->conexion = $conexion;
    }

    // CREATE
    public function guardar($idRol, $idEstado, $nombre, $apPaterno, $apMaterno, $clave, $correo) {
        $sql = "INSERT INTO USUARIO (
                    ID_ROL,
                    ID_ESTADO,
                    NOMBRE,
                    APELLIDO_PATERNO,
                    APELLIDO_MATERNO,
                    CONTRASENA,
                    CORREO
                )
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);

        $claveEncriptada = md5($clave);

        return $stmt->execute([
            $idRol,
            $idEstado,
            $nombre,
            $apPaterno,
            $apMaterno,
            $claveEncriptada,
            $correo
        ]);
    }

    // UPDATE
    public function editar($id, $idRol, $idEstado, $nombre, $apPaterno, $apMaterno, $correo) {
        $sql = "UPDATE USUARIO
                SET ID_ROL = ?,
                    ID_ESTADO = ?,
                    NOMBRE = ?,
                    APELLIDO_PATERNO = ?,
                    APELLIDO_MATERNO = ?,
                    CORREO = ?
                WHERE ID_USUARIO = ?";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            $idRol,
            $idEstado,
            $nombre,
            $apPaterno,
            $apMaterno,
            $correo,
            $id
        ]);
    }

    // UPDATE de clave
    public function cambiarClave($id, $claveNueva) {
        $sql = "UPDATE USUARIO
                SET CONTRASENA = ?
                WHERE ID_USUARIO = ?";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            md5($claveNueva),
            $id
        ]);
    }

    // DELET
    public function eliminar($idUsuario) {
        // Solo cambiamos el estado del usuario a Inactivo
        $stmt = $this->conexion->prepare(
            "UPDATE USUARIO
             SET ID_ESTADO = 2
             WHERE ID_USUARIO = :id"
        );

        return $stmt->execute([
            ':id' => $idUsuario
        ]);
    }

    // SELECT todos
    public function listar() {
        $sql = "SELECT U.ID_USUARIO,
                       U.NOMBRE,
                       U.APELLIDO_PATERNO,
                       U.APELLIDO_MATERNO,
                       U.CORREO,
                       R.NOMBRE_ROL,
                       E.NOMBRE_ESTADO,
                       U.ID_ROL,
                       U.ID_ESTADO
                FROM USUARIO U
                LEFT JOIN ROL R ON U.ID_ROL = R.ID_ROL
                LEFT JOIN ESTADO E ON U.ID_ESTADO = E.ID_ESTADO
                ORDER BY U.ID_USUARIO DESC";

        $stmt = $this->conexion->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // SELECT por ID
    public function obtenerPorId($id) {
        $sql = "SELECT *
                FROM USUARIO
                WHERE ID_USUARIO = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Validar login (correo + clave)
    public function validarLogin($correo, $clave) {
        $sql = "SELECT U.ID_USUARIO,
                       U.NOMBRE,
                       U.CORREO,
                       U.CONTRASENA,
                       R.NOMBRE_ROL,
                       E.NOMBRE_ESTADO
                FROM USUARIO U
                INNER JOIN ROL R
                    ON U.ID_ROL = R.ID_ROL
                INNER JOIN ESTADO E
                    ON U.ID_ESTADO = E.ID_ESTADO
                WHERE U.CORREO = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$correo]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Comparamos la contraseña ingresada con el "MD5" almacenado
        if ($usuario && md5($clave) === $usuario['CONTRASENA']) {

            // No guardar la contraseña en la sesión/datos retornados
            unset($usuario['CONTRASENA']);

            return $usuario;
        }

        return false;
    }

    public function listarPorRol($nombreRol) {
        $sql = "SELECT U.ID_USUARIO,
                       U.NOMBRE,
                       U.APELLIDO_PATERNO
                FROM USUARIO U
                INNER JOIN ROL R
                    ON U.ID_ROL = R.ID_ROL
                WHERE R.NOMBRE_ROL = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$nombreRol]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>