<?php
// clases/TipoCultivo.php
class TipoCultivo {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // CREATE
    public function guardar($nombre, $nombreCientifico, $tiempoCosecha, $frecuenciaRiego, $frecuenciaFertilizacion, $observaciones) {
        $sql = "INSERT INTO TIPO_CULTIVO (NOMBRE, NOMBRE_CIENTIFICO, TIEMPO_COSECHA, FRECUENCIA_RIEGO, FRECUENCIA_FERTILIZACION, OBSERVACIONES)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$nombre, $nombreCientifico, $tiempoCosecha, $frecuenciaRiego, $frecuenciaFertilizacion, $observaciones]);
    }

    // UPDATE
    public function editar($id, $nombre, $nombreCientifico, $tiempoCosecha, $frecuenciaRiego, $frecuenciaFertilizacion, $observaciones) {
        $sql = "UPDATE TIPO_CULTIVO
                SET NOMBRE = ?, NOMBRE_CIENTIFICO = ?, TIEMPO_COSECHA = ?, FRECUENCIA_RIEGO = ?, FRECUENCIA_FERTILIZACION = ?, OBSERVACIONES = ?
                WHERE ID_TIPO_CULTIVO = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$nombre, $nombreCientifico, $tiempoCosecha, $frecuenciaRiego, $frecuenciaFertilizacion, $observaciones, $id]);
    }

    // DELETE
    public function eliminar($id) {
        $sql = "DELETE FROM TIPO_CULTIVO WHERE ID_TIPO_CULTIVO = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    // SELECT todos
    public function listar() {
        $sql = "SELECT * FROM TIPO_CULTIVO ORDER BY NOMBRE ASC";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // SELECT por id
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM TIPO_CULTIVO WHERE ID_TIPO_CULTIVO = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>