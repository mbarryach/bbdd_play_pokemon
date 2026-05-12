<?php
// ─────────────────────────────────────────────────────────
//  models/EquipoModel.php
//
//  Responsabilidad: acceso a datos de equipos.
// ─────────────────────────────────────────────────────────

class EquipoModel {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = ConexionDB::getInstancia()->getConexion();
    }

    // ── Todos los equipos con estadísticas ─────────────
    public function obtenerTodos(): array {
        return $this->pdo->query('SELECT * FROM v_estadisticas_equipos')->fetchAll();
    }

    // ── Lista simple (id + nombre) para selects HTML ───
    public function obtenerLista(): array {
        return $this->pdo->query(
            'SELECT id, nombre FROM equipos ORDER BY nombre'
        )->fetchAll();
    }

    // ── Equipo por ID ───────────────────────────────────
    public function obtenerPorId(int $id): array|false {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM v_estadisticas_equipos WHERE id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // ── Crear equipo (admin) ────────────────────────────
    public function crear(string $nombre): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO equipos (nombre) VALUES (?)'
        );
        $stmt->execute([trim($nombre)]);
        return (int) $this->pdo->lastInsertId();
    }

    // ── Renombrar equipo (admin) ────────────────────────
    public function editar(int $id, string $nombre): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE equipos SET nombre = ? WHERE id = ?'
        );
        return $stmt->execute([trim($nombre), $id]);
    }

    // ── Eliminar equipo (admin) ─────────────────────────
    public function eliminar(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM equipos WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
