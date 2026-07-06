<?php
// clases/Cultivo.php
class Cultivo {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // CREATE
    public function guardar($idHuerta, $idTipoCultivo, $idGrupo, $idEstado, $fechaSiembra, $cantidad) {
        $sql = "INSERT INTO CULTIVO (ID_HUERTA, ID_TIPO_CULTIVO, ID_GRUPO, ID_ESTADO, FECHA_SIEMBRA, CANTIDAD)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$idHuerta, $idTipoCultivo, $idGrupo, $idEstado, $fechaSiembra, $cantidad]);
    }

    // UPDATE
    public function editar($id, $idHuerta, $idTipoCultivo, $idGrupo, $idEstado, $fechaSiembra, $cantidad) {
        $sql = "UPDATE CULTIVO
                SET ID_HUERTA = ?, ID_TIPO_CULTIVO = ?, ID_GRUPO = ?, ID_ESTADO = ?, FECHA_SIEMBRA = ?, CANTIDAD = ?
                WHERE ID_CULTIVO = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$idHuerta, $idTipoCultivo, $idGrupo, $idEstado, $fechaSiembra, $cantidad, $id]);
    }

    // DELETE
    public function eliminar($id) {
        $sql = "DELETE FROM CULTIVO WHERE ID_CULTIVO = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    // SELECT todos
    public function listar() {
        $sql = "SELECT C.ID_CULTIVO, C.FECHA_SIEMBRA, C.CANTIDAD,
                       H.NOMBRE AS NOMBRE_HUERTA, T.NOMBRE AS NOMBRE_TIPO,
                       G.NOMBRE AS NOMBRE_GRUPO, E.NOMBRE_ESTADO,
                       C.ID_HUERTA, C.ID_TIPO_CULTIVO, C.ID_GRUPO, C.ID_ESTADO
                FROM CULTIVO C
                LEFT JOIN HUERTA H ON C.ID_HUERTA = H.ID_HUERTA
                LEFT JOIN TIPO_CULTIVO T ON C.ID_TIPO_CULTIVO = T.ID_TIPO_CULTIVO
                LEFT JOIN GRUPO_ESTUDIANTIL G ON C.ID_GRUPO = G.ID_GRUPO
                LEFT JOIN ESTADO E ON C.ID_ESTADO = E.ID_ESTADO
                ORDER BY C.ID_CULTIVO DESC";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // SELECT por id
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM CULTIVO WHERE ID_CULTIVO = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Seguimiento del crecimiento: historial de actividades de un cultivo ordenado por fecha
    public function seguimiento($idCultivo) {
        $sql = "SELECT A.FECHA_ACTIVIDAD, TA.NOMBRE_ACTIVIDAD, A.DESCRIPCION, U.NOMBRE AS RESPONSABLE
                FROM ACTIVIDAD A
                LEFT JOIN TIPO_ACTIVIDAD TA ON A.ID_TIPO_ACTIVIDAD = TA.ID_TIPO_ACTIVIDAD
                LEFT JOIN USUARIO U ON A.ID_USUARIO = U.ID_USUARIO
                WHERE A.ID_CULTIVO = ?
                ORDER BY A.FECHA_ACTIVIDAD ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$idCultivo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Combos auxiliares
    public function listarHuertas() {
        return $this->conexion->query("SELECT ID_HUERTA, NOMBRE FROM HUERTA")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarTiposCultivo() {
        return $this->conexion->query("SELECT ID_TIPO_CULTIVO, NOMBRE FROM TIPO_CULTIVO")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarGrupos() {
        return $this->conexion->query("SELECT ID_GRUPO, NOMBRE FROM GRUPO_ESTUDIANTIL")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarEstados() {
        return $this->conexion->query("SELECT ID_ESTADO, NOMBRE_ESTADO FROM ESTADO")->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
