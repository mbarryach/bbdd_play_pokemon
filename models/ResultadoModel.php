<?php
// ─────────────────────────────────────────────────────────
//  models/ResultadoModel.php
//
//  Columnas reales de v_resultados:
//  resultado_id, emparejamiento_id, torneo_id, torneo,
//  ronda, fase, mesa, Hora_Programada,
//  jugador1_id, jugador1, player1_id, Juegos_Jugador1,
//  jugador2_id, jugador2, player2_id, Juegos_Jugador2,
//  ganador, Verificado, Hora_Finalizacion
// ─────────────────────────────────────────────────────────

class ResultadoModel {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = ConexionDB::getInstancia()->getConexion();
    }

    // ── Todos los resultados (paginados) ───────────────
    public function obtenerTodos(int $pagina = 1, int $pp = 15): array {
        $offset = ($pagina - 1) * $pp;
        $stmt   = $this->pdo->prepare('SELECT * FROM v_resultados LIMIT ? OFFSET ?');
        $stmt->execute([$pp, $offset]);
        return $stmt->fetchAll();
    }

    public function contarTotal(): int {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM v_resultados')->fetchColumn();
    }

    // ── Resultados de un torneo concreto ───────────────
    public function obtenerPorTorneo(int $torneoId, int $pagina = 1, int $pp = 15): array {
        $offset = ($pagina - 1) * $pp;
        $stmt   = $this->pdo->prepare(
            'SELECT * FROM v_resultados WHERE torneo_id = ? LIMIT ? OFFSET ?'
        );
        $stmt->execute([$torneoId, $pp, $offset]);
        return $stmt->fetchAll();
    }

    public function contarPorTorneo(int $torneoId): int {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM v_resultados WHERE torneo_id = ?'
        );
        $stmt->execute([$torneoId]);
        return (int) $stmt->fetchColumn();
    }

    // ── Próximas partidas ───────────────────────────────
    public function obtenerProximas(int $limite = 5): array {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM v_proximas_partidas LIMIT ?'
        );
        $stmt->execute([$limite]);
        return $stmt->fetchAll();
    }

    // ── Registrar/actualizar resultado (árbitro/admin) ──
    public function registrar(int $emparejamientoId, ?int $ganadorId,
                              int $juegosJ1, int $juegosJ2): bool {
        // ¿Ya existe un resultado para este emparejamiento?
        $stmt = $this->pdo->prepare(
            'SELECT ID_Resultado FROM RESULTADO_PARTIDO WHERE ID_Emparejamiento = ?'
        );
        $stmt->execute([$emparejamientoId]);
        $existente = $stmt->fetchColumn();

        $esEmpate = ($ganadorId === null) ? 1 : 0;

        if ($existente) {
            $stmt = $this->pdo->prepare(
                'UPDATE RESULTADO_PARTIDO
                 SET    ID_Ganador = ?, Empate = ?,
                        Juegos_Jugador1 = ?, Juegos_Jugador2 = ?,
                        Hora_Finalizacion = NOW()
                 WHERE  ID_Emparejamiento = ?'
            );
            return $stmt->execute([$ganadorId, $esEmpate, $juegosJ1, $juegosJ2, $emparejamientoId]);
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO RESULTADO_PARTIDO
                    (ID_Emparejamiento, ID_Ganador, Empate,
                     Juegos_Jugador1, Juegos_Jugador2, Hora_Finalizacion)
             VALUES (?, ?, ?, ?, ?, NOW())'
        );
        return $stmt->execute([$emparejamientoId, $ganadorId, $esEmpate, $juegosJ1, $juegosJ2]);
    }
}
