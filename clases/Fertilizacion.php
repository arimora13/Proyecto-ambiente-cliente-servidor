<?php
// clases/Fertilizacion.php
// El modelo de datos entregado no tiene una tabla propia para fertilizaciones: son
// un tipo de ACTIVIDAD (tabla TIPO_ACTIVIDAD).
// Esta clase filtra siempre por ese tipo para no inventar tablas nuevas.
class Fertilizacion {

    private $conexion;
    private $nombreTipo = 'Fertilizacion';

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    private function obtenerIdTipo() {
        $sql = "SELECT ID_TIPO_ACTIVIDAD FROM TIPO_ACTIVIDAD WHERE NOMBRE_ACTIVIDAD = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$this->nombreTipo]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ? $fila['ID_TIPO_ACTIVIDAD'] : null;
    }

    // CREATE
    public function guardar($idCultivo, $idUsuario, $fecha, $descripcion) {
        $idTipo = $this->obtenerIdTipo();
        $sql = "INSERT INTO ACTIVIDAD (ID_CULTIVO, ID_USUARIO, ID_TIPO_ACTIVIDAD, FECHA_ACTIVIDAD, DESCRIPCION)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$idCultivo, $idUsuario, $idTipo, $fecha, $descripcion]);
    }

    // UPDATE
    public function editar($id, $idCultivo, $fecha, $descripcion) {
        $sql = "UPDATE ACTIVIDAD SET ID_CULTIVO = ?, FECHA_ACTIVIDAD = ?, DESCRIPCION = ?
                WHERE ID_ACTIVIDAD = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$idCultivo, $fecha, $descripcion, $id]);
    }

    // DELETE
    public function eliminar($id) {
        $sql = "DELETE FROM ACTIVIDAD WHERE ID_ACTIVIDAD = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    // SELECT todos (solo riegos)
    public function listar() {
        $sql = "SELECT A.ID_ACTIVIDAD, A.FECHA_ACTIVIDAD, A.DESCRIPCION,
                       C.ID_CULTIVO, TC.NOMBRE AS NOMBRE_CULTIVO, U.NOMBRE AS RESPONSABLE
                FROM ACTIVIDAD A
                INNER JOIN TIPO_ACTIVIDAD TA ON A.ID_TIPO_ACTIVIDAD = TA.ID_TIPO_ACTIVIDAD
                LEFT JOIN CULTIVO C ON A.ID_CULTIVO = C.ID_CULTIVO
                LEFT JOIN TIPO_CULTIVO TC ON C.ID_TIPO_CULTIVO = TC.ID_TIPO_CULTIVO
                LEFT JOIN USUARIO U ON A.ID_USUARIO = U.ID_USUARIO
                WHERE TA.NOMBRE_ACTIVIDAD = ?
                ORDER BY A.FECHA_ACTIVIDAD DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$this->nombreTipo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM ACTIVIDAD WHERE ID_ACTIVIDAD = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarCultivos() {
        return $this->conexion->query("SELECT ID_CULTIVO FROM CULTIVO")->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
