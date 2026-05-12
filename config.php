<?php
// ─────────────────────────────────────────────────────────
//  config.php  —  Raíz del proyecto
//  Ubicación: /competencia/config.php
// ─────────────────────────────────────────────────────────

// ── Base de datos ──────────────────────────────────────
define('DB_HOST',    'localhost');
define('DB_PORT',    3307);
define('DB_NAME',    'torneo_db');
define('DB_CHARSET', 'utf8mb4');

// ── Credenciales por rol (usuario MySQL → permisos reales en BD) ───
// Cada rol usa un usuario MySQL distinto con los GRANT ajustados.
define('DB_CREDENTIALS', [
    'admin'    => ['user' => 'admin_torneo', 'pass' => 'Admin_TCG_2024!'],
    'arbitro'  => ['user' => 'arbitro',      'pass' => 'Arbitro_TCG_2024!'],
    'consulta' => ['user' => 'consulta',     'pass' => 'Consulta_TCG_2024!'],
    // Fallback para la tabla de autenticación (solo lee usuarios_admin)
    'auth'     => ['user' => 'root',         'pass' => ''],
]);

// ── Roles del sistema ─────────────────────────────────
define('ROL_ADMIN',    'admin');
define('ROL_ARBITRO',  'arbitro');
define('ROL_CONSULTA', 'consulta');

// ── Aplicación ─────────────────────────────────────────
define('APP_NAME',    'Liga Pokémon');
define('APP_VERSION', '1.0.0');
define('APP_URL',     'http://localhost/competencia');

// ── Rutas absolutas ────────────────────────────────────
define('BASE_PATH',   __DIR__);
define('CLASES_PATH', BASE_PATH . '/clases');
define('ADMIN_PATH',  BASE_PATH . '/admin');
define('INC_PATH',    BASE_PATH . '/includes');

// ── Sesión ─────────────────────────────────────────────
define('SESSION_NAME',    'liga_pokemon_session');
define('SESSION_TIMEOUT', 1800);   // 30 min de inactividad

// ── Modo debug ─────────────────────────────────────────
define('DEBUG_MODE', true);   // ← false en producción

if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// ── Zona horaria ───────────────────────────────────────
date_default_timezone_set('Europe/Madrid');

// ── Autoload de clases ─────────────────────────────────
spl_autoload_register(function (string $clase): void {
    $archivo = CLASES_PATH . '/' . $clase . '.php';
    if (file_exists($archivo)) {
        require_once $archivo;
    }
});
