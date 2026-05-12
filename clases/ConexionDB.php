<?php
// ─────────────────────────────────────────────────────────
//  clases/ConexionDB.php  —  Singleton PDO con soporte de roles
//  Uso: ConexionDB::getInstancia($rol)->getConexion()
// ─────────────────────────────────────────────────────────

if (!defined('DB_HOST')) {
    require_once __DIR__ . '/../config.php';
}

class ConexionDB {

    /** @var ConexionDB[] Una instancia por rol */
    private static array $instancias = [];
    private PDO $conexion;

    private function __construct(string $rol) {
        $credenciales = DB_CREDENTIALS[$rol] ?? DB_CREDENTIALS['auth'];

        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
            );

            $this->conexion = new PDO($dsn, $credenciales['user'], $credenciales['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);

        } catch (PDOException $e) {
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                die('Error de conexión: ' . $e->getMessage());
            }
            die('Error interno del servidor. Inténtalo más tarde.');
        }
    }

    /**
     * Devuelve la instancia PDO para el rol indicado.
     * Si no se especifica rol, usa el rol de la sesión activa.
     */
    public static function getInstancia(string $rol = ''): self {
        if ($rol === '') {
            $rol = $_SESSION['rol'] ?? 'auth';
        }

        if (!isset(self::$instancias[$rol])) {
            self::$instancias[$rol] = new self($rol);
        }

        return self::$instancias[$rol];
    }

    public function getConexion(): PDO {
        return $this->conexion;
    }

    // __clone puede ser private: no es un magic method de PHP externo.
    private function __clone() {}

    // __wakeup DEBE ser public en PHP 8.1+ (magic method contract).
    // Se protege lanzando excepción, no con visibilidad privada.
    // 'never' (PHP 8.1+) indica que siempre lanza excepción, nunca retorna.
    public function __wakeup(): never {
        throw new \Exception(
            'ConexionDB no puede deserializarse. ' .
            'Usa ConexionDB::getInstancia() para obtener la conexión.'
        );
    }
}
