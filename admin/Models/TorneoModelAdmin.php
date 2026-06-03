<?php
class TorneoModelAdmin {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = ConexionDB::getInstancia()->getConexion();
    }

    public function obtenerTodos(): array {
        $stmt = $this->pdo->query("
            SELECT nombre, fecha_inicio, fecha_fin, Ubicacion, Pais
            FROM torneo
            ORDER BY fecha_inicio DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>