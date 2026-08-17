<?php
// clases/Huerta.php
class Huerta {

    private PDO $conexion;

    public function __construct(PDO $conexion) {
        $this->conexion = $conexion;
    }

    // CREATE
    public function guardar($idInstitucion, $idEstado, $nombre, $areaM2, $descripcion) {
        $sql = "INSERT INTO HUERTA (ID_INSTITUCION, ID_ESTADO, NOMBRE, AREA_M2, DESCRIPCION)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$idInstitucion, $idEstado, $nombre, $areaM2, $descripcion]);
    }

    // UPDATE
    public function editar($id, $idInstitucion, $idEstado, $nombre, $areaM2, $descripcion) {
        $sql = "UPDATE HUERTA
                SET ID_INSTITUCION = ?, ID_ESTADO = ?, NOMBRE = ?, AREA_M2 = ?, DESCRIPCION = ?
                WHERE ID_HUERTA = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$idInstitucion, $idEstado, $nombre, $areaM2, $descripcion, $id]);
    }

    // DELETE
    public function eliminar($idHuerta) {
    
    $stmt1 = $this->conexion->prepare("DELETE FROM REPORTE WHERE ID_HUERTA = ?");
    $stmt1->execute([$idHuerta]);

    
    $stmt2 = $this->conexion->prepare("DELETE FROM ACTIVIDAD WHERE ID_CULTIVO IN (SELECT ID_CULTIVO FROM CULTIVO WHERE ID_HUERTA = ?)");
    $stmt2->execute([$idHuerta]);

    $stmt3 = $this->conexion->prepare("DELETE FROM ALERTA WHERE ID_CULTIVO IN (SELECT ID_CULTIVO FROM CULTIVO WHERE ID_HUERTA = ?)");
    $stmt3->execute([$idHuerta]);

   
    $stmt4 = $this->conexion->prepare("DELETE FROM CULTIVO WHERE ID_HUERTA = ?");
    $stmt4->execute([$idHuerta]);

   
    $stmt5 = $this->conexion->prepare("DELETE FROM HUERTA WHERE ID_HUERTA = ?");
    return $stmt5->execute([$idHuerta]);
}

    // SELECT todas
    public function listar() {
        $sql = "SELECT H.ID_HUERTA, H.NOMBRE, H.AREA_M2, H.DESCRIPCION,
                       H.ID_INSTITUCION, H.ID_ESTADO,
                       I.NOMBRE AS NOMBRE_INSTITUCION, E.NOMBRE_ESTADO
                FROM HUERTA H
                LEFT JOIN INSTITUCION I ON H.ID_INSTITUCION = I.ID_INSTITUCION
                LEFT JOIN ESTADO E ON H.ID_ESTADO = E.ID_ESTADO
                ORDER BY H.ID_HUERTA DESC";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // SELECT por id
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM HUERTA WHERE ID_HUERTA = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Combos auxiliares
    public function listarInstituciones() {
        return $this->conexion->query("SELECT ID_INSTITUCION, NOMBRE FROM INSTITUCION ORDER BY NOMBRE")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarEstados() {
        return $this->conexion->query("SELECT ID_ESTADO, NOMBRE_ESTADO FROM ESTADO")->fetchAll(PDO::FETCH_ASSOC);
    }
}
