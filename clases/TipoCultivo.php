<?php
// clases/TipoCultivo.php
class TipoCultivo {

    private PDO $conexion;

    public function __construct(PDO $conexion) {
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
    try {
        $this->conexion->beginTransaction();

        $stmtCultivos = $this->conexion->prepare("SELECT ID_CULTIVO FROM CULTIVO WHERE ID_TIPO_CULTIVO = ?");
        $stmtCultivos->execute([$id]);
        $cultivos = $stmtCultivos->fetchAll(PDO::FETCH_COLUMN);

        foreach ($cultivos as $idCultivo) {
            $this->conexion->prepare("DELETE FROM ACTIVIDAD WHERE ID_CULTIVO = ?")->execute([$idCultivo]);
            $this->conexion->prepare("DELETE FROM ALERTA WHERE ID_CULTIVO = ?")->execute([$idCultivo]);
        }

        $this->conexion->prepare("DELETE FROM CULTIVO WHERE ID_TIPO_CULTIVO = ?")->execute([$id]);

        $stmtTipo = $this->conexion->prepare("DELETE FROM TIPO_CULTIVO WHERE ID_TIPO_CULTIVO = ?");
        $resultado = $stmtTipo->execute([$id]);

        $this->conexion->commit();
        return $resultado;

    } catch (Exception $e) {
        $this->conexion->rollBack();
        return false;
    }
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