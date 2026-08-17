<?php
// clases/Institucion.php
class Institucion {

    private PDO $conexion;

    public function __construct(PDO $conexion) {
        $this->conexion = $conexion;
    }

   public function guardar($idProvincia, $idCanton, $idDistrito, $otrasSenas, $nombre, $telefono) {
        try {
            $this->conexion->beginTransaction();

            $sqlDireccion = "INSERT INTO DIRECCION (ID_PROVINCIA, ID_CANTON, ID_DISTRITO, OTRAS_SENAS)
                             VALUES (?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($sqlDireccion);
            $stmt->execute([$idProvincia, $idCanton, $idDistrito, $otrasSenas]);
            $idDireccion = $this->conexion->lastInsertId();

            $sqlInstitucion = "INSERT INTO INSTITUCION (ID_DIRECCION, NOMBRE) VALUES (?, ?)";
            $stmt2 = $this->conexion->prepare($sqlInstitucion);
            $stmt2->execute([$idDireccion, $nombre]);
            $idInstitucion = $this->conexion->lastInsertId();

            if (!empty($telefono)) {
                $sqlTelefono = "INSERT INTO TELEFONO (ID_INSTITUCION, TELEFONO) VALUES (?, ?)";
                $stmt3 = $this->conexion->prepare($sqlTelefono);
                $stmt3->execute([$idInstitucion, $telefono]);
            }

            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            return false;
        }
    }

    public function editar($id, $idDireccion, $idProvincia, $idCanton, $idDistrito, $otrasSenas, $nombre, $telefono) {
        try {
            $this->conexion->beginTransaction();

            $sqlDireccion = "UPDATE DIRECCION
                             SET ID_PROVINCIA = ?, ID_CANTON = ?, ID_DISTRITO = ?, OTRAS_SENAS = ?
                             WHERE ID_DIRECCION = ?";
            $stmt = $this->conexion->prepare($sqlDireccion);
            $stmt->execute([$idProvincia, $idCanton, $idDistrito, $otrasSenas, $idDireccion]);

            $sqlInstitucion = "UPDATE INSTITUCION SET NOMBRE = ? WHERE ID_INSTITUCION = ?";
            $stmt2 = $this->conexion->prepare($sqlInstitucion);
            $stmt2->execute([$nombre, $id]);

            // Eliminar teléfono anterior y reinsertar si se envió un valor
            $this->conexion->prepare("DELETE FROM TELEFONO WHERE ID_INSTITUCION = ?")->execute([$id]);
            if (!empty($telefono)) {
                $sqlTelefono = "INSERT INTO TELEFONO (ID_INSTITUCION, TELEFONO) VALUES (?, ?)";
                $stmt3 = $this->conexion->prepare($sqlTelefono);
                $stmt3->execute([$id, $telefono]);
            }

            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            return false;
        }
    }

   public function eliminar($id) {
        try {
            $datos = $this->obtenerPorId($id);
            if (!$datos) {
                return false;
            }

            $this->conexion->beginTransaction();

            $this->conexion->prepare("DELETE FROM TELEFONO WHERE ID_INSTITUCION = ?")->execute([$id]);
            $this->conexion->prepare("DELETE FROM INSTITUCION WHERE ID_INSTITUCION = ?")->execute([$id]);
            if (!empty($datos['ID_DIRECCION'])) {
                $this->conexion->prepare("DELETE FROM DIRECCION WHERE ID_DIRECCION = ?")->execute([$datos['ID_DIRECCION']]);
            }

            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            return false;
        }
    }

    public function listar() {
        $sql = "SELECT I.ID_INSTITUCION, I.NOMBRE,
                       D.OTRAS_SENAS,
                       P.NOMBRE_PROVINCIA, C.NOMBRE_CANTON, DI.NOMBRE_DISTRITO,
                       T.TELEFONO
                FROM INSTITUCION I
                LEFT JOIN DIRECCION D  ON I.ID_DIRECCION = D.ID_DIRECCION
                LEFT JOIN PROVINCIA P  ON D.ID_PROVINCIA = P.ID_PROVINCIA
                LEFT JOIN CANTON C     ON D.ID_CANTON = C.ID_CANTON
                LEFT JOIN DISTRITO DI  ON D.ID_DISTRITO = DI.ID_DISTRITO
                LEFT JOIN TELEFONO T   ON I.ID_INSTITUCION = T.ID_INSTITUCION
                ORDER BY I.ID_INSTITUCION DESC";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT I.ID_INSTITUCION, I.NOMBRE, I.ID_DIRECCION,
                       D.ID_PROVINCIA, D.ID_CANTON, D.ID_DISTRITO, D.OTRAS_SENAS,
                       T.TELEFONO
                FROM INSTITUCION I
                LEFT JOIN DIRECCION D ON I.ID_DIRECCION = D.ID_DIRECCION
                LEFT JOIN TELEFONO T  ON I.ID_INSTITUCION = T.ID_INSTITUCION
                WHERE I.ID_INSTITUCION = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarProvincias() {
        return $this->conexion->query("SELECT ID_PROVINCIA, NOMBRE_PROVINCIA FROM PROVINCIA ORDER BY NOMBRE_PROVINCIA")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarCantones() {
        return $this->conexion->query("SELECT ID_CANTON, NOMBRE_CANTON FROM CANTON ORDER BY NOMBRE_CANTON")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarDistritos() {
        return $this->conexion->query("SELECT ID_DISTRITO, NOMBRE_DISTRITO FROM DISTRITO ORDER BY NOMBRE_DISTRITO")->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>