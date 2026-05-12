<?php
// ─────────────────────────────────────────────────────────
//  admin/dashboard.php
// ─────────────────────────────────────────────────────────
require_once __DIR__ . '/../config.php';
Auth::requerirLogin();

$paginaAdmin = 'dashboard';
$rol = Auth::getRol();
$pdo = ConexionDB::getInstancia()->getConexion();
$msg = $_GET['msg'] ?? '';

// ── Estadísticas (con try/catch por si las tablas aún no existen) ──
$stats = [];
$queries = [
    'torneos'   => 'SELECT COUNT(*) FROM torneos',
    'jugadores' => 'SELECT COUNT(*) FROM jugadores',
    'partidos'  => 'SELECT COUNT(*) FROM partidos',
    'jugados'   => 'SELECT COUNT(*) FROM partidos WHERE jugado = 1',
];
foreach ($queries as $clave => $sql) {
    try {
        $stats[$clave] = (int) $pdo->query($sql)->fetchColumn();
    } catch (PDOException) {
        $stats[$clave] = '—';
    }
}

// ── Últimos 6 partidos ──────────────────────────────────
$ultimosPartidos = [];
try {
    $ultimosPartidos = $pdo->query(
        "SELECT p.id,
                el.nombre AS local,
                ev.nombre AS visitante,
                p.goles_local, p.goles_visitante,
                p.fecha, p.jugado
         FROM   partidos p
         JOIN   equipos el ON el.id = p.equipo_local_id
         JOIN   equipos ev ON ev.id = p.equipo_visitante_id
         ORDER  BY p.fecha DESC LIMIT 6"
    )->fetchAll();
} catch (PDOException) {}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — <?= htmlspecialchars(APP_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin.css">
</head>
<body>

<?php include __DIR__ . '/includes/sidebar.php'; ?>

<div class="admin-main">

    <!-- Topbar -->
    <header class="topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle" onclick="document.body.classList.toggle('sidebar-open')" aria-label="Menú">☰</button>
            <h1 class="page-title">Dashboard</h1>
        </div>
        <div class="topbar-right">
            <?php if ($msg === 'sin_permiso'): ?>
                <span class="topbar-alert">🚫 Sin permiso para esa sección</span>
            <?php endif; ?>
            <span class="topbar-user">
                <?= Auth::getIconoRol() ?>
                <strong><?= htmlspecialchars(Auth::getUsuario()) ?></strong>
                <span class="badge badge-<?= $rol ?>"><?= Auth::getLabelRol() ?></span>
            </span>
        </div>
    </header>

    <div class="admin-content">

        <!-- Bienvenida -->
        <div class="welcome-banner">
            <div>
                <h2>¡Bienvenido de nuevo, <?= htmlspecialchars(Auth::getUsuario()) ?>! <?= Auth::getIconoRol() ?></h2>
                <p>Acceso como <strong><?= Auth::getLabelRol() ?></strong> · <?= date('l, d \d\e F \d\e Y') ?></p>
            </div>
            <?php if (Auth::esAdmin()): ?>
            <a href="<?= APP_URL ?>/admin/torneos.php" class="btn btn-primary">+ Nuevo torneo</a>
            <?php endif; ?>
        </div>

        <!-- Stats grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">🏆</div>
                <div class="stat-body">
                    <div class="stat-value"><?= $stats['torneos'] ?></div>
                    <div class="stat-label">Torneos</div>
                </div>
                <div class="stat-trend up">↑</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🎴</div>
                <div class="stat-body">
                    <div class="stat-value"><?= $stats['jugadores'] ?></div>
                    <div class="stat-label">Jugadores</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⚔️</div>
                <div class="stat-body">
                    <div class="stat-value"><?= $stats['partidos'] ?></div>
                    <div class="stat-label">Partidos totales</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-body">
                    <div class="stat-value"><?= $stats['jugados'] ?></div>
                    <div class="stat-label">Jugados</div>
                </div>
            </div>
        </div>

        <!-- Accesos rápidos según rol -->
        <div class="section-header">
            <h3 class="section-title">Acciones rápidas</h3>
        </div>
        <div class="quick-actions">
            <?php if (Auth::tieneRol([ROL_ADMIN, ROL_ARBITRO])): ?>
            <a href="<?= APP_URL ?>/admin/resultados.php" class="action-card">
                <span class="action-icon">📊</span>
                <span class="action-label">Registrar resultado</span>
            </a>
            <a href="<?= APP_URL ?>/admin/inscripciones.php" class="action-card">
                <span class="action-icon">📋</span>
                <span class="action-label">Gestionar inscripciones</span>
            </a>
            <a href="<?= APP_URL ?>/admin/partidos.php" class="action-card">
                <span class="action-icon">⚔️</span>
                <span class="action-label">Ver emparejamientos</span>
            </a>
            <?php endif; ?>
            <a href="<?= APP_URL ?>/admin/jugadores.php" class="action-card">
                <span class="action-icon">🎴</span>
                <span class="action-label">Consultar jugadores</span>
            </a>
            <a href="<?= APP_URL ?>/admin/torneos.php" class="action-card">
                <span class="action-icon">🏆</span>
                <span class="action-label">Ver torneos</span>
            </a>
            <?php if (Auth::esAdmin()): ?>
            <a href="<?= APP_URL ?>/admin/usuarios.php" class="action-card action-card--admin">
                <span class="action-icon">👥</span>
                <span class="action-label">Gestionar usuarios</span>
            </a>
            <?php endif; ?>
        </div>

        <!-- Últimos partidos -->
        <div class="section-header">
            <h3 class="section-title">Últimos partidos</h3>
            <?php if (Auth::tieneRol([ROL_ADMIN, ROL_ARBITRO])): ?>
            <a href="<?= APP_URL ?>/admin/partidos.php" class="btn btn-ghost btn-sm">Ver todos →</a>
            <?php endif; ?>
        </div>

        <?php if (empty($ultimosPartidos)): ?>
        <div class="empty-state">
            <span class="empty-icon">⚔️</span>
            <p>No hay partidos registrados aún.</p>
            <?php if (Auth::esAdmin()): ?>
            <a href="<?= APP_URL ?>/admin/nuevo_partido.php" class="btn btn-primary btn-sm">+ Añadir partido</a>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Local</th>
                        <th>Resultado</th>
                        <th>Visitante</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <?php if (Auth::tieneRol([ROL_ADMIN, ROL_ARBITRO])): ?>
                        <th>Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ultimosPartidos as $p): ?>
                    <tr>
                        <td class="td-id">#<?= (int)$p['id'] ?></td>
                        <td class="td-equipo"><?= htmlspecialchars($p['local']) ?></td>
                        <td class="td-resultado">
                            <?php if ($p['jugado']): ?>
                                <span class="marcador"><?= (int)$p['goles_local'] ?> — <?= (int)$p['goles_visitante'] ?></span>
                            <?php else: ?>
                                <span class="vs">VS</span>
                            <?php endif; ?>
                        </td>
                        <td class="td-equipo"><?= htmlspecialchars($p['visitante']) ?></td>
                        <td><?= htmlspecialchars($p['fecha']) ?></td>
                        <td>
                            <?php if ($p['jugado']): ?>
                                <span class="badge badge-verde">✔ Jugado</span>
                            <?php else: ?>
                                <span class="badge badge-amarillo">⏳ Pendiente</span>
                            <?php endif; ?>
                        </td>
                        <?php if (Auth::tieneRol([ROL_ADMIN, ROL_ARBITRO])): ?>
                        <td class="td-acciones">
                            <a href="<?= APP_URL ?>/admin/editar_resultado.php?id=<?= (int)$p['id'] ?>"
                               class="btn btn-warning btn-sm">Editar</a>
                            <?php if (Auth::esAdmin()): ?>
                            <a href="<?= APP_URL ?>/admin/eliminar_partido.php?id=<?= (int)$p['id'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Eliminar este partido?')">Eliminar</a>
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

    </div><!-- /.admin-content -->
</div><!-- /.admin-main -->

<script>
// Cerrar sidebar al hacer clic fuera (móvil)
document.addEventListener('click', function(e) {
    if (document.body.classList.contains('sidebar-open') &&
        !e.target.closest('.sidebar') &&
        !e.target.closest('.sidebar-toggle')) {
        document.body.classList.remove('sidebar-open');
    }
});
</script>

</body>
</html>
