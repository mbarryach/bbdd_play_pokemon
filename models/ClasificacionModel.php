<?php

class ClasificacionModel {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = ConexionDB::getInstancia()->getConexion();
    }

    public function obtenerPorTorneo(int $torneoId): array {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM v_clasificacion
            WHERE torneo_id = ?
            ORDER BY posicion_final ASC, puntos DESC
        ");
    
        $stmt->execute([$torneoId]);
        return $stmt->fetchAll();
    }
}


