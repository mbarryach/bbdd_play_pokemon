<?php
require_once '../../../clases/ConexionDB.php';  // ← Línea añadida


class JugadorModelAdmin {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = ConexionDB::getInstancia()->getConexion();
    }
    
    public function obtenerTodos(): array {
        $stmt = $this->pdo->query("
            SELECT ID_Jugador, nombre_completo, pais, division, 
                   cp_totales, cp_temporada_actual, torneos_jugados
            FROM v_jugadores
            ORDER BY cp_totales DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function sumarCP(int $id, int $cantidad): bool {
        $stmt = $this->pdo->prepare("
            UPDATE jugador 
            SET cp_totales = cp_totales + ?, 
                cp_temporada_actual = cp_temporada_actual + ? 
            WHERE ID_Jugador = ?
        ");
        return $stmt->execute([$cantidad, $cantidad, $id]);
    }

    public function restarCP(int $id, int $cantidad): bool {
        $stmt = $this->pdo->prepare("
            UPDATE jugador
            SET cp_totales = cp_totales - ?, 
                cp_temporada_actual = cp_temporada_actual - ? 
            WHERE ID_Jugador = ?
        ");
        return $stmt->execute([$cantidad, $cantidad, $id]);
    }

    public function eliminarJugador(int $id): bool {
        try {
            $this->pdo->beginTransaction();

            // 1. Eliminar emparejamientos donde aparece como jugador1 o jugador2
            $stmt = $this->pdo->prepare("DELETE FROM emparejamiento WHERE ID_Jugador1 = ? OR ID_Jugador2 = ?");
            $stmt->execute([$id, $id]);

            // 2. Eliminar registros en otras tablas que referencien al jugador (si las hay)
            // Por ejemplo: `partido`, `inscripcion`, `resultado`, etc.
            // Añade las que correspondan a tu BD
            // $stmt = $this->pdo->prepare("DELETE FROM inscripcion WHERE ID_Jugador = ?");
            // $stmt->execute([$id]);

            // 3. Finalmente eliminar el jugador
            $stmt = $this->pdo->prepare("DELETE FROM jugadores WHERE ID_Jugador = ?");
            $stmt->execute([$id]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error eliminando jugador $id: " . $e->getMessage());
            return false;
        }
    }

    public function aplicarSancion(int $id, int $penalizacion = 20): bool {
        $stmt = $this->pdo->prepare("
            UPDATE jugador 
            SET cp_totales = cp_totales - ?, 
                cp_temporada_actual = cp_temporada_actual - ? 
            WHERE ID_Jugador = ?
        ");
        return $stmt->execute([$penalizacion, $penalizacion, $id]);
    }
}
?>