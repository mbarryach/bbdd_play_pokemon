<?php
class JugadorModel {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = ConexionDB::getInstancia()->getConexion();
    }
    

    public function obtenerTodos(): array {
        // $stmt = $this->pdo->query("SELECT * FROM v_jugadores ORDER BY cp_totales DESC");

        $stmt = $this->pdo->query("
            SELECT nombre_completo, pais, division, cp_totales, cp_temporada_actual, torneos_jugados
            FROM v_jugadores
            ORDER BY cp_totales DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>