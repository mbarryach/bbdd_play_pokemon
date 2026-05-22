<?php
// ─────────────────────────────────────────────────────────
//  models/TorneoModel.php
//
//  Columnas reales de v_torneos:
//  ID_Torneo, Nombre, Tipo_Torneo, Fecha_Inicio, Fecha_Fin,
//  Ubicacion, Pais, Num_Rondas_Suizas, Tamanio_Top_Cut,
//  temporada, total_inscritos, total_partidas,
//  partidas_jugadas, estado
// ─────────────────────────────────────────────────────────

class TorneoModel {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = ConexionDB::getInstancia()->getConexion();
    }

    // ── Lista completa para la página pública ──────────
    public function obtenerTodos(): array {
        return $this->pdo->query('SELECT * FROM v_torneos')->fetchAll();
    }

    // ── Solo ID + Nombre para el selector HTML ─────────
    public function obtenerLista(): array {
        return $this->pdo->query(
            'SELECT ID_Torneo, Nombre, estado FROM v_torneos ORDER BY Fecha_Inicio DESC'
        )->fetchAll();
    }

    // ── Torneo en curso o más reciente (default selector) ─
    public function obtenerActivo(): array|false {
        return $this->pdo->query(
            "SELECT * FROM v_torneos
             WHERE  estado = 'En curso'
             ORDER  BY Fecha_Inicio DESC
             LIMIT  1"
        )->fetch() ?: $this->pdo->query(
            'SELECT * FROM v_torneos ORDER BY Fecha_Inicio DESC LIMIT 1'
        )->fetch();
    }

    // ── Un torneo por ID ────────────────────────────────
    public function obtenerPorId(int $id): array|false {
        $stmt = $this->pdo->prepare('SELECT * FROM v_torneos WHERE ID_Torneo = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // ── Crear torneo (solo admin) ───────────────────────
    public function crear(array $d): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO TORNEO
                (Nombre, Tipo_Torneo, Fecha_Inicio, Fecha_Fin,
                 Ubicacion, Pais, Num_Rondas_Suizas, Tamanio_Top_Cut)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            trim($d['nombre']),
            $d['tipo']            ?? 'Suizo',
            $d['fecha_inicio']    ?? null,
            $d['fecha_fin']       ?? null,
            $d['ubicacion']       ?? null,
            $d['pais']            ?? null,
            (int)($d['rondas']    ?? 0),
            (int)($d['top_cut']   ?? 0),
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    // ── Editar torneo (solo admin) ──────────────────────
    public function editar(int $id, array $d): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE TORNEO
             SET    Nombre = ?, Tipo_Torneo = ?, Fecha_Inicio = ?,
                    Fecha_Fin = ?, Ubicacion = ?, Pais = ?,
                    Num_Rondas_Suizas = ?, Tamanio_Top_Cut = ?
             WHERE  ID_Torneo = ?'
        );
        return $stmt->execute([
            trim($d['nombre']),
            $d['tipo']         ?? 'Suizo',
            $d['fecha_inicio'] ?? null,
            $d['fecha_fin']    ?? null,
            $d['ubicacion']    ?? null,
            $d['pais']         ?? null,
            (int)($d['rondas']  ?? 0),
            (int)($d['top_cut'] ?? 0),
            $id,
        ]);
    }

    // ── Eliminar torneo (solo admin) ────────────────────
    public function eliminar(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM TORNEO WHERE ID_Torneo = ?');
        return $stmt->execute([$id]);
    }
}
