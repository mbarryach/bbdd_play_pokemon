<?php
// ─────────────────────────────────────────────────────────
//  models/UsuarioModel.php
//
//  Responsabilidad: gestión de usuarios del panel admin.
//
//  SEGURIDAD DE ROLES:
//  El método registrarPublico() asigna SIEMPRE 'consulta',
//  ignorando cualquier dato externo. Los roles privilegiados
//  solo los asigna crearConRol(), que debe llamarse
//  exclusivamente desde código interno o paneles de admin.
// ─────────────────────────────────────────────────────────

class UsuarioModel {

    private PDO $pdo;

    public function __construct() {
        // Siempre usamos 'auth' para leer/escribir usuarios
        $this->pdo = ConexionDB::getInstancia('auth')->getConexion();
    }

    // ── Registro público ────────────────────────────────
    // El rol SIEMPRE es 'consulta'. No acepta rol por parámetro.
    // Devuelve true si se creó, false si el usuario ya existe.
    public function registrarPublico(string $usuario, string $password): bool {
        if ($this->existeUsuario($usuario)) {
            return false;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        $stmt = $this->pdo->prepare(
            'INSERT INTO usuarios (usuario, password, rol, activo)
             VALUES (?, ?, \'consulta\', 1)'
        );

        return $stmt->execute([trim($usuario), $hash]);
    }

    // ── Crear usuario con rol específico (solo interno) ─
    // Este método NO debe estar accesible desde formularios públicos.
    // Solo se llama desde: scripts de consola, panel de admin.
    public function crearConRol(string $usuario, string $password, string $rol): bool {
        $rolesPermitidos = ['admin', 'arbitro', 'consulta'];
        if (!in_array($rol, $rolesPermitidos, true)) {
            throw new \InvalidArgumentException("Rol '$rol' no permitido.");
        }

        if ($this->existeUsuario($usuario)) {
            return false;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $this->pdo->prepare(
            'INSERT INTO usuarios (usuario, password, rol, activo)
             VALUES (?, ?, ?, 1)'
        );
        return $stmt->execute([trim($usuario), $hash, $rol]);
    }

    // ── Cambiar contraseña ──────────────────────────────
    public function cambiarPassword(int $id, string $nuevaPassword): bool {
        $hash = password_hash($nuevaPassword, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $this->pdo->prepare(
            'UPDATE usuarios SET password = ? WHERE id = ?'
        );
        return $stmt->execute([$hash, $id]);
    }

    // ── Activar / desactivar usuario (admin) ───────────
    public function setActivo(int $id, bool $activo): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE usuarios SET activo = ? WHERE id = ?'
        );
        return $stmt->execute([(int)$activo, $id]);
    }

    // ── Listar todos los usuarios (admin) ───────────────
    public function obtenerTodos(): array {
        return $this->pdo->query(
            'SELECT id, usuario, rol, activo, created_at
             FROM   usuarios
             ORDER  BY created_at DESC'
        )->fetchAll();
    }

    // ── Comprobar si un nombre de usuario ya existe ─────
    public function existeUsuario(string $usuario): bool {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM usuarios WHERE usuario = ?'
        );
        $stmt->execute([trim($usuario)]);
        return (int) $stmt->fetchColumn() > 0;
    }

    // ── Eliminar usuario (admin) ────────────────────────
    public function eliminar(int $id): bool {
        $stmt = $this->pdo->prepare(
            'DELETE FROM usuarios WHERE id = ?'
        );
        return $stmt->execute([$id]);
    }
}
