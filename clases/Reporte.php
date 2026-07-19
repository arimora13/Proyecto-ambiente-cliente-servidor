<?php
// clases/Reporte.php
class Reporte {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    //CRUD BASADO EN SECUENCIAS DML
    // CREATE - recibe los valores del id de la huerta, fecha del reporte, el periodo en que se evalua y una descripcion a gusto
    public function guardar($idHuerta, $fecha, $periodo, $descripcion) {
        $sql= "INSERT INTO REPORTE (ID_HUERTA, FECHA, PERIODO, DESCRIPCION) VALUES (?, ?, ?, ?)";
        $stmt= $this->conexion->prepare($sql);
        return $stmt->execute([$idHuerta, $fecha, $periodo, $descripcion]);
    }

    // UPDATE
    public function editar($id, $idHuerta, $fecha, $periodo, $descripcion) {
        $sql= "UPDATE REPORTE SET ID_HUERTA = ?, FECHA = ?, PERIODO = ?, DESCRIPCION = ? WHERE ID_REPORTE = ?";
        $stmt= $this->conexion->prepare($sql);
        return $stmt->execute([$idHuerta, $fecha, $periodo, $descripcion, $id]);
    }

    // DELETE - elimina por id
    public function eliminar($id) {
        $sql= "DELETE FROM REPORTE WHERE ID_REPORTE = ?";
        $stmt= $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    // SELECT todos los atributos de un reporte con inner join
    public function listar() {
        $sql= "SELECT R.ID_REPORTE, R.FECHA, R.PERIODO, R.DESCRIPCION, H.NOMBRE AS NOMBRE_HUERTA, R.ID_HUERTA
                FROM REPORTE R
                LEFT JOIN HUERTA H ON R.ID_HUERTA = H.ID_HUERTA
                ORDER BY R.FECHA DESC";
                //el inner trae el nombre de la huerta
        return $this->conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    //creado para buscar un reporte por id
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM REPORTE WHERE ID_REPORTE = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //creacio especialmente para devolver el id y nombre de la huerta
    public function listarHuertas() {
        return $this->conexion->query("SELECT ID_HUERTA, NOMBRE FROM HUERTA")->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
