<?php
// clases/GrupoEstudiantil.php
class GrupoEstudiantil {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // CREATE
    public function guardar($idDocente, $nombre, $grado, $seccion, $anyo) {
        $sql = "INSERT INTO GRUPO_ESTUDIANTIL (ID_DOCENTE_RESPONSABLE, NOMBRE, GRADO, SECCION, ANYO)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$idDocente, $nombre, $grado, $seccion, $anyo]);
    }

    // UPDATE
    public function editar($id, $idDocente, $nombre, $grado, $seccion, $anyo) {
        $sql = "UPDATE GRUPO_ESTUDIANTIL
                SET ID_DOCENTE_RESPONSABLE = ?, NOMBRE = ?, GRADO = ?, SECCION = ?, ANYO = ?
                WHERE ID_GRUPO = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$idDocente, $nombre, $grado, $seccion, $anyo, $id]);
    }

    // DELETE
    public function eliminar($id) {
        $sql = "DELETE FROM GRUPO_ESTUDIANTIL WHERE ID_GRUPO = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    // SELECT todos
    public function listar() {
        $sql = "SELECT G.ID_GRUPO, G.NOMBRE, G.GRADO, G.SECCION, G.ANYO,
                       G.ID_DOCENTE_RESPONSABLE,
                       U.NOMBRE AS NOMBRE_DOCENTE, U.APELLIDO_PATERNO AS APELLIDO_DOCENTE
                FROM GRUPO_ESTUDIANTIL G
                LEFT JOIN USUARIO U ON G.ID_DOCENTE_RESPONSABLE = U.ID_USUARIO
                ORDER BY G.ID_GRUPO DESC";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // SELECT por id
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM GRUPO_ESTUDIANTIL WHERE ID_GRUPO = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Combo auxiliar: solo usuarios con rol Docente pueden ser responsables de un grupo
    public function listarDocentes() {
        $sql = "SELECT U.ID_USUARIO, U.NOMBRE, U.APELLIDO_PATERNO
                FROM USUARIO U
                INNER JOIN ROL R ON U.ID_ROL = R.ID_ROL
                WHERE R.NOMBRE_ROL = 'Docente'";
        return $this->conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>