<?php
// ─────────────────────────────────────────────────────────
//  models/JugadorModel.php
//
//  Columnas reales de v_jugadores:
//  ID_Jugador, Nombre, Apellidos, nombre_completo,
//  Player_ID, Email, Pais, Division,
//  CP_Totales, CP_Temporada_Actual,
//  temporada_actual, torneos_jugados
// ─────────────────────────────────────────────────────────

class JugadorModel {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = ConexionDB::getInstancia()->getConexion();
    }

    // ── Todos los jugadores ─────────────────────────────
    public function obtenerTodos(): array {
        return $this->pdo->query('SELECT * FROM v_jugadores')->fetchAll();
    }

    // ── Buscar por nombre, apellido o Player_ID ─────────
    public function buscar(string $q): array {
        $like = '%' . $q . '%';
        $stmt = $this->pdo->prepare(
            'SELECT * FROM v_jugadores
             WHERE  nombre_completo LIKE ?
                OR  Player_ID       LIKE ?
                OR  Division        LIKE ?'
        );
        $stmt->execute([$like, $like, $like]);
        return $stmt->fetchAll();
    }

    // ── Jugadores de un torneo concreto ────────────────
    public function obtenerPorTorneo(int $torneoId): array {
        $stmt = $this->pdo->prepare(
            'SELECT v.* FROM v_jugadores v
             JOIN   INSCRIPCION i ON i.ID_Jugador = v.ID_Jugador
             WHERE  i.ID_Torneo = ?'
        );
        $stmt->execute([$torneoId]);
        return $stmt->fetchAll();
    }

    // ── Jugador por ID ──────────────────────────────────
    public function obtenerPorId(int $id): array|false {
        $stmt = $this->pdo->prepare('SELECT * FROM v_jugadores WHERE ID_Jugador = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // ── Crear jugador (admin) ───────────────────────────
    public function crear(array $d): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO JUGADOR (Nombre, Apellidos, Player_ID, Email, Pais, Division)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            trim($d['nombre']),
            trim($d['apellidos']),
            $d['player_id'] ?? null,
            $d['email']     ?? null,
            $d['pais']      ?? null,
            $d['division']  ?? null,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    // ── Editar jugador (admin) ──────────────────────────
    public function editar(int $id, array $d): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE JUGADOR
             SET Nombre = ?, Apellidos = ?, Player_ID = ?,
                 Email = ?, Pais = ?, Division = ?
             WHERE ID_Jugador = ?'
        );
        return $stmt->execute([
            trim($d['nombre']),
            trim($d['apellidos']),
            $d['player_id'] ?? null,
            $d['email']     ?? null,
            $d['pais']      ?? null,
            $d['division']  ?? null,
            $id,
        ]);
    }
}
