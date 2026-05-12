<?php
// ─────────────────────────────────────────────────────────
//  models/ClasificacionModel.php
//
//  Responsabilidad: acceso a datos de clasificación.
//  Lee directamente de la vista SQL v_clasificacion,
//  que ya contiene toda la lógica de cálculo de puntos.
//
//  El modelo NO conoce ni HTML ni lógica de negocio:
//  solo sabe hablar con la base de datos.
// ─────────────────────────────────────────────────────────

class ClasificacionModel {

    private PDO $pdo;

    public function __construct() {
        // Conexión con el rol activo en sesión (o 'auth' si no hay)
        $this->pdo = ConexionDB::getInstancia()->getConexion();
    }

    // ── Tabla de clasificación completa ────────────────
    // Devuelve todos los equipos ordenados por puntos.
    public function obtenerTabla(): array {
        $stmt = $this->pdo->query('SELECT * FROM v_clasificacion');
        return $stmt->fetchAll();
    }

    // ── Clasificación de un torneo concreto ────────────
    // Útil si en el futuro hay varios torneos simultáneos.
    public function obtenerTablaPorTorneo(int $torneoId): array {
        $stmt = $this->pdo->prepare(
            'SELECT c.*
             FROM   v_clasificacion c
             JOIN   inscripciones i ON i.equipo_id = c.equipo_id
             WHERE  i.torneo_id = ?'
        );
        $stmt->execute([$torneoId]);
        return $stmt->fetchAll();
    }

    // ── Top N equipos (para widgets del dashboard) ─────
    public function obtenerTop(int $limite = 5): array {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM v_clasificacion LIMIT ?'
        );
        $stmt->execute([$limite]);
        return $stmt->fetchAll();
    }
}
