<?php
// ─────────────────────────────────────────────────────────
//  models/ResultadoModel.php
//
//  Responsabilidad: acceso a datos de partidos/resultados.
//  Lee de v_resultados (jugados) y v_proximos_partidos.
//  Los árbitros también pueden insertar/actualizar resultados.
// ─────────────────────────────────────────────────────────

class ResultadoModel {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = ConexionDB::getInstancia()->getConexion();
    }

    // ── Últimos N resultados ────────────────────────────
    public function obtenerUltimos(int $limite = 10): array {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM v_resultados LIMIT ?'
        );
        $stmt->execute([$limite]);
        return $stmt->fetchAll();
    }

    // ── Todos los resultados (con paginación opcional) ──
    public function obtenerTodos(int $pagina = 1, int $porPagina = 20): array {
        $offset = ($pagina - 1) * $porPagina;
        $stmt   = $this->pdo->prepare(
            'SELECT * FROM v_resultados LIMIT ? OFFSET ?'
        );
        $stmt->execute([$porPagina, $offset]);
        return $stmt->fetchAll();
    }

    // ── Total de resultados (para paginar) ─────────────
    public function contarTotal(): int {
        return (int) $this->pdo->query(
            'SELECT COUNT(*) FROM v_resultados'
        )->fetchColumn();
    }

    // ── Próximos partidos ───────────────────────────────
    public function obtenerProximos(int $limite = 5): array {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM v_proximos_partidos LIMIT ?'
        );
        $stmt->execute([$limite]);
        return $stmt->fetchAll();
    }

    // ── Resultado de un partido concreto ────────────────
    public function obtenerPorId(int $id): array|false {
        $stmt = $this->pdo->prepare(
            'SELECT p.*, el.nombre AS equipo_local, ev.nombre AS equipo_visitante
             FROM   partidos p
             JOIN   equipos el ON el.id = p.equipo_local_id
             JOIN   equipos ev ON ev.id = p.equipo_visitante_id
             WHERE  p.id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // ── Registrar resultado (árbitro/admin) ────────────
    // Valida que los goles no sean negativos antes de insertar.
    public function registrarResultado(int $id, int $golesLocal, int $golesVisitante): bool {
        if ($golesLocal < 0 || $golesVisitante < 0) {
            return false;
        }

        $stmt = $this->pdo->prepare(
            'UPDATE partidos
             SET    goles_local = ?, goles_visitante = ?, jugado = 1
             WHERE  id = ?'
        );
        return $stmt->execute([$golesLocal, $golesVisitante, $id]);
    }

    // ── Crear partido (solo admin) ──────────────────────
    public function crearPartido(int $localId, int $visitanteId, string $fecha, string $ronda = ''): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO partidos (equipo_local_id, equipo_visitante_id, fecha, ronda, jugado)
             VALUES (?, ?, ?, ?, 0)'
        );
        $stmt->execute([$localId, $visitanteId, $fecha, $ronda]);
        return (int) $this->pdo->lastInsertId();
    }

    // ── Eliminar partido (solo admin) ──────────────────
    public function eliminar(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM partidos WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
