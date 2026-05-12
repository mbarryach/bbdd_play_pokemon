<?php
// ─────────────────────────────────────────────────────────
//  models/JugadorModel.php
//
//  Responsabilidad: acceso a datos de jugadores.
//  Lee de v_jugadores (incluye nombre de equipo).
// ─────────────────────────────────────────────────────────

class JugadorModel {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = ConexionDB::getInstancia()->getConexion();
    }

    // ── Todos los jugadores (con equipo) ────────────────
    public function obtenerTodos(): array {
        return $this->pdo->query('SELECT * FROM v_jugadores')->fetchAll();
    }

    // ── Jugadores de un equipo concreto ────────────────
    public function obtenerPorEquipo(int $equipoId): array {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM v_jugadores WHERE equipo_id = ?'
        );
        $stmt->execute([$equipoId]);
        return $stmt->fetchAll();
    }

    // ── Buscar por nombre o apodo ───────────────────────
    // Usa LIKE con parámetro seguro (no concatenación directa).
    public function buscar(string $termino): array {
        $like = '%' . $termino . '%';
        $stmt = $this->pdo->prepare(
            'SELECT * FROM v_jugadores
             WHERE  nombre_completo LIKE ? OR apodo LIKE ?'
        );
        $stmt->execute([$like, $like]);
        return $stmt->fetchAll();
    }

    // ── Jugador por ID ──────────────────────────────────
    public function obtenerPorId(int $id): array|false {
        $stmt = $this->pdo->prepare('SELECT * FROM v_jugadores WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // ── Crear jugador (admin) ───────────────────────────
    public function crear(array $datos): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO jugadores (nombre, apellidos, apodo, email, equipo_id, activo)
             VALUES (?, ?, ?, ?, ?, 1)'
        );
        $stmt->execute([
            $datos['nombre'],
            $datos['apellidos'],
            $datos['apodo']    ?? null,
            $datos['email']    ?? null,
            $datos['equipo_id'] ?? null,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    // ── Editar jugador (admin) ──────────────────────────
    public function editar(int $id, array $datos): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE jugadores
             SET    nombre = ?, apellidos = ?, apodo = ?, email = ?, equipo_id = ?
             WHERE  id = ?'
        );
        return $stmt->execute([
            $datos['nombre'],
            $datos['apellidos'],
            $datos['apodo']    ?? null,
            $datos['email']    ?? null,
            $datos['equipo_id'] ?? null,
            $id,
        ]);
    }

    // ── Eliminar jugador (admin) ────────────────────────
    public function eliminar(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM jugadores WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
