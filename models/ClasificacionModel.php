<?php
// ─────────────────────────────────────────────────────────
//  models/ClasificacionModel.php
//
//  Columnas reales de v_clasificacion:
//  torneo_id, torneo, temporada, jugador_id, jugador,
//  Player_ID, Division, partidas_jugadas, victorias,
//  derrotas, empates, puntos,
//  omw_percentage, pmw_percentage, oom_percentage,
//  posicion_final
// ─────────────────────────────────────────────────────────

class ClasificacionModel {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = ConexionDB::getInstancia()->getConexion();
    }

    // ── Clasificación de un torneo concreto ────────────
    // Ordena por posicion_final (calculada en CLASIFICACION_SUIZA).
    // Si posicion_final es null, ordena por puntos como fallback.
    public function obtenerPorTorneo(int $torneoId): array {
        $stmt = $this->pdo->prepare(
            'SELECT *
             FROM   v_clasificacion
             WHERE  torneo_id = ?
             ORDER  BY
                COALESCE(posicion_final, 9999) ASC,
                puntos DESC,
                victorias DESC'
        );
        $stmt->execute([$torneoId]);
        return $stmt->fetchAll();
    }

    // ── Top N jugadores de un torneo (widget) ──────────
    public function obtenerTop(int $torneoId, int $limite = 5): array {
        $stmt = $this->pdo->prepare(
            'SELECT jugador, puntos, victorias, derrotas, omw_percentage
             FROM   v_clasificacion
             WHERE  torneo_id = ?
             ORDER  BY COALESCE(posicion_final, 9999) ASC, puntos DESC
             LIMIT  ?'
        );
        $stmt->execute([$torneoId, $limite]);
        return $stmt->fetchAll();
    }
}
