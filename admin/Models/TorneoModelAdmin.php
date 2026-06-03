<?php
require_once '../../../clases/ConexionDB.php';

class TorneoModelAdmin {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = ConexionDB::getInstancia()->getConexion();
    }

    public function obtenerTodos(): array {
        $stmt = $this->pdo->query("
            SELECT ID_Torneo, nombre, Tipo_Torneo, fecha_inicio, fecha_fin, Ubicacion, Pais,
                   Num_Rondas_Suizas, Tamanio_Top_Cut, ID_Temporada
            FROM torneo
            ORDER BY fecha_inicio DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTemporadas(): array {
        $stmt = $this->pdo->query("SELECT ID_Temporada FROM temporada ORDER BY ID_Temporada DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function crear(array $datos): bool {
        $stmt = $this->pdo->prepare("
            INSERT INTO torneo 
            (Nombre, Tipo_Torneo, fecha_inicio, fecha_fin, Ubicacion, Pais, Num_Rondas_Suizas, Tamanio_Top_Cut, ID_Temporada)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $datos['Nombre'],
            $datos['Tipo_Torneo'],
            $datos['fecha_inicio'],
            $datos['fecha_fin'],
            $datos['Ubicacion'],
            $datos['Pais'],
            $datos['Num_Rondas_Suizas'],
            $datos['Tamanio_Top_Cut'],
            $datos['ID_Temporada']
        ]);
    }

    public function eliminar(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM torneo WHERE ID_Torneo = ?");
        return $stmt->execute([$id]);
    }
}
?>