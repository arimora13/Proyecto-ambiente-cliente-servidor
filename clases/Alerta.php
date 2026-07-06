<?php
// clases/Alerta.php
class Alerta {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // CREATE
    public function guardar($idCultivo, $idUsuario, $idTipoActividad, $idEstado, $descripcion) {
        $sql = "INSERT INTO ALERTA (ID_CULTIVO, ID_USUARIO, ID_TIPO_ACTIVIDAD, ID_ESTADO, DESCRIPCION)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$idCultivo, $idUsuario, $idTipoActividad, $idEstado, $descripcion]);
    }

    // UPDATE (usada principalmente para cambiar el estado: Pendiente -> Atendida)
    public function editar($id, $idEstado, $descripcion) {
        $sql = "UPDATE ALERTA SET ID_ESTADO = ?, DESCRIPCION = ? WHERE ID_ALERTA = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$idEstado, $descripcion, $id]);
    }

    // DELETE
    public function eliminar($id) {
        $sql = "DELETE FROM ALERTA WHERE ID_ALERTA = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    // SELECT todos
    public function listar() {
        $sql = "SELECT AL.ID_ALERTA, AL.DESCRIPCION, C.ID_CULTIVO, TA.NOMBRE_ACTIVIDAD, E.NOMBRE_ESTADO, AL.ID_ESTADO
                FROM ALERTA AL
                LEFT JOIN CULTIVO C ON AL.ID_CULTIVO = C.ID_CULTIVO
                LEFT JOIN TIPO_ACTIVIDAD TA ON AL.ID_TIPO_ACTIVIDAD = TA.ID_TIPO_ACTIVIDAD
                LEFT JOIN ESTADO E ON AL.ID_ESTADO = E.ID_ESTADO
                ORDER BY AL.ID_ALERTA DESC";
        return $this->conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM ALERTA WHERE ID_ALERTA = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function contarPendientes() {
        $sql = "SELECT COUNT(*) AS TOTAL FROM ALERTA AL
                INNER JOIN ESTADO E ON AL.ID_ESTADO = E.ID_ESTADO
                WHERE E.NOMBRE_ESTADO = 'Pendiente'";
        $fila = $this->conexion->query($sql)->fetch(PDO::FETCH_ASSOC);
        return $fila['TOTAL'];
    }
}
?>
