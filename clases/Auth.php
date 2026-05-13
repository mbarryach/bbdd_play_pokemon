<?php
// ─────────────────────────────────────────────────────────
//  clases/Auth.php  —  Autenticación y control de acceso por rol
// ─────────────────────────────────────────────────────────

if (!defined('DB_HOST')) {
    require_once __DIR__ . '/../config.php';
}

class Auth {

    // ── Arranca la sesión (seguro, solo una vez) ────────
    public static function iniciarSesion(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            session_start();
        }
    }

    // ── ¿Hay sesión viva y válida? ──────────────────────
    public static function check(): bool {
        return self::estaAutenticado();
    }

    public static function estaAutenticado(): bool {
        self::iniciarSesion();

        if (empty($_SESSION['admin_id'])) {
            return false;
        }

        // Timeout por inactividad
        if (isset($_SESSION['ultimo_acceso'])) {
            if ((time() - $_SESSION['ultimo_acceso']) > SESSION_TIMEOUT) {
                self::cerrarSesion();
                return false;
            }
        }

        $_SESSION['ultimo_acceso'] = time();
        return true;
    }

    // ── Obtener el rol actual ───────────────────────────
    public static function getRol(): string {
        self::iniciarSesion();
        return $_SESSION['rol'] ?? '';
    }

    // ── Comprobar si el usuario tiene un rol concreto ───
    public static function esRol(string $rol): bool {
        return self::getRol() === $rol;
    }

    public static function esAdmin(): bool {
        return self::esRol(ROL_ADMIN);
    }

    public static function esArbitro(): bool {
        return self::esRol(ROL_ARBITRO);
    }

    public static function esConsulta(): bool {
        return self::esRol(ROL_CONSULTA);
    }

    // ── ¿Tiene alguno de los roles indicados? ──────────
    public static function tieneRol(array $roles): bool {
        return in_array(self::getRol(), $roles, true);
    }

    // ── Guard genérico: requiere login ──────────────────
    public static function requerirLogin(): void {
        if (!self::estaAutenticado()) {
            header('Location: ' . APP_URL . '/admin/login.php?msg=sesion_expirada');
            exit;
        }
    }

    // ── Guard con rol: redirige si no tiene permiso ─────
    public static function requerirRol(array $rolesPermitidos): void {
        self::requerirLogin();
        if (!self::tieneRol($rolesPermitidos)) {
            header('Location: ' . APP_URL . '/admin/dashboard.php?msg=sin_permiso');
            exit;
        }
    }

    // ── Login: valida credenciales y abre sesión ────────
    public static function login(string $usuario, string $password): bool {
        self::iniciarSesion();

        $usuario = trim($usuario);
        if ($usuario === '' || $password === '') {
            return false;
        }

        if (strlen($usuario) > 60 || strlen($password) > 128) {
            return false;
        }

        // Conexión de autenticación (usuario root, solo lee usuarios)
        $pdo  = ConexionDB::getInstancia('auth')->getConexion();
        $stmt = $pdo->prepare(
            'SELECT id, usuario, password, rol
             FROM   usuarios
             WHERE  usuario = ? AND activo = 1
             LIMIT  1'
        );
        $stmt->execute([$usuario]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);   // Previene session fixation

            $_SESSION['admin_id']      = $admin['id'];
            $_SESSION['admin_usuario'] = $admin['usuario'];
            $_SESSION['rol']           = $admin['rol'];
            $_SESSION['ultimo_acceso'] = time();

            return true;
        }

        return false;
    }

    // ── Cierre de sesión completo ───────────────────────
    public static function cerrarSesion(): void {
        self::iniciarSesion();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }

    // ── Datos de sesión ─────────────────────────────────
    public static function getUsuario(): string {
        return $_SESSION['admin_usuario'] ?? '';
    }

    public static function getId(): int {
        return (int)($_SESSION['admin_id'] ?? 0);
    }

    // ── Etiqueta legible del rol ────────────────────────
    public static function getLabelRol(): string {
        return match(self::getRol()) {
            ROL_ADMIN    => 'Administrador',
            ROL_ARBITRO  => 'Árbitro',
            ROL_CONSULTA => 'Consulta',
            default      => 'Desconocido',
        };
    }

    // ── Icono del rol ───────────────────────────────────
    public static function getIconoRol(): string {
        return match(self::getRol()) {
            ROL_ADMIN    => '👑',
            ROL_ARBITRO  => '⚖️',
            ROL_CONSULTA => '👁️',
            default      => '❓',
        };
    }

    // ── Utilidad: generar hash seguro ───────────────────
    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
}
