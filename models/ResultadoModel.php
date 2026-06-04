<?php
class ResultadoModel {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = ConexionDB::getInstancia()->getConexion();
    }

    public function obtenerUltimos(int $cantidad = 10): array {
        $stmt = $this->pdo->prepare("
            SELECT torneo, ronda, jugador1, jugador2,
                   Juegos_Jugador1, Juegos_Jugador2, ganador
            FROM v_resultados
            ORDER BY torneo DESC, ronda DESC
            LIMIT :cantidad
        ");
        
        $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>