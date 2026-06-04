<?php
// ─────────────────────────────────────────────────────────
//  admin/dashboard.php
// ─────────────────────────────────────────────────────────
require_once __DIR__ . '/../config.php';
Auth::requerirLogin();

$paginaAdmin = 'dashboard';
$rol         = Auth::getRol();
$msg         = $_GET['msg'] ?? '';
$pdo         = ConexionDB::getInstancia()->getConexion();

// ── Stats con tablas reales ────────────────────────────
$stats = [];
foreach ([
    'torneos'   => 'SELECT COUNT(*) FROM TORNEO',
    'jugadores' => 'SELECT COUNT(*) FROM JUGADOR',
    'partidas'  => 'SELECT COUNT(*) FROM EMPAREJAMIENTO',
    'jugadas'   => 'SELECT COUNT(*) FROM RESULTADO_PARTIDO',
] as $k => $sql) {
    try   { $stats[$k] = (int) $pdo->query($sql)->fetchColumn(); }
    catch (PDOException) { $stats[$k] = '—'; }
}

// ── Últimas 6 partidas (v_resultados ya tiene los JOINs) ─
$ultimasPartidas = [];
try {
    $ultimasPartidas = $pdo->query(
        'SELECT torneo, ronda, jugador1, jugador2,
                Juegos_Jugador1, Juegos_Jugador2, ganador
         FROM   v_resultados LIMIT 6'
    )->fetchAll();
} catch (PDOException) {}

// ── Próximas 5 partidas ────────────────────────────────
$proximasPartidas = [];
try {
    $proximasPartidas = $pdo->query(
        'SELECT torneo, ronda, jugador1, jugador2, Hora_Programada
         FROM   v_proximas_partidas LIMIT 5'
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

<?php include ADMIN_PATH . '/includes/sidebar.php'; ?>

<div class="admin-main">

    <header class="topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle"
                    onclick="document.body.classList.toggle('sidebar-open')" aria-label="Menú">☰</button>
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
                <h2>¡Bienvenido, <?= htmlspecialchars(Auth::getUsuario()) ?>! <?= Auth::getIconoRol() ?></h2>
                <p>Acceso como <strong><?= Auth::getLabelRol() ?></strong> · <?= date('d/m/Y') ?></p>
            </div>

        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">🏆</div>
                <div class="stat-body">
                    <div class="stat-value"><?= $stats['torneos'] ?></div>
                    <div class="stat-label">Torneos</div>
                </div>
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
                    <div class="stat-value"><?= $stats['partidas'] ?></div>
                    <div class="stat-label">Partidas totales</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-body">
                    <div class="stat-value"><?= $stats['jugadas'] ?></div>
                    <div class="stat-label">Con resultado</div>
                </div>
            </div>
        </div>

        <!-- Acciones rápidas según rol -->
        <div class="section-header"><h3 class="section-title">Acciones rápidas</h3></div>
        <div class="quick-actions">
            <a href="<?= APP_URL ?>/views/clasification/index.php"  class="action-card">
                <span class="action-icon">📊</span><span class="action-label">Clasificación</span>
            </a>
            <a href="<?= APP_URL ?>/views/jugadores/index.php" class="action-card">
                <span class="action-icon">🎴</span><span class="action-label">Jugadores</span>
            </a>
            <a href="<?= APP_URL ?>/views/torneos/index.php"   class="action-card">
                <span class="action-icon">🏆</span><span class="action-label">Torneos</span>
            </a>
            <a href="<?= APP_URL ?>/views/resultado/index.php" class="action-card">
                <span class="action-icon">⚔️</span><span class="action-label">Resultados</span>
            </a>
            <?php if (Auth::tieneRol([ROL_ADMIN, ROL_ARBITRO])): ?>
            <a href="<?= APP_URL ?>/admin/views/torneos/index.php"   class="action-card">
                <span class="action-icon">⚙️</span><span class="action-label">Gestionar torneos</span>
            </a>
            <a href="<?= APP_URL ?>/admin/views/jugadores/index.php" class="action-card">
                <span class="action-icon">🪪</span><span class="action-label">Gestionar jugadores</span>
            </a>
            <?php endif; ?>
            <?php if (Auth::esAdmin()): ?>
            <a href="<?= APP_URL ?>/admin/views/usuarios/index.php" class="action-card action-card--admin">
                <span class="action-icon">👥</span><span class="action-label">Usuarios del sistema</span>
            </a>
            <?php endif; ?>
        </div>

        <!-- Dos columnas: últimas + próximas -->
        <div class="db-grid">

            <section>
                <div class="section-header">
                    <h3 class="section-title">Últimas partidas</h3>
                    <a href="<?= APP_URL ?>/views/resultado/index.php" class="btn btn-ghost btn-sm">Ver todas →</a>
                </div>
                <?php if (empty($ultimasPartidas)): ?>
                <div class="empty-state">
                    <span class="empty-icon">⚔️</span><p>Sin partidas aún.</p>
                </div>
                <?php else: ?>
                <div class="partidas-list">
                    <?php foreach ($ultimasPartidas as $p): ?>
                    <div class="partida-row">
                        <span class="partida-meta">
                            🏆 <?= htmlspecialchars($p['torneo']) ?>
                            <?= $p['ronda'] ? '· R'.(int)$p['ronda'] : '' ?>
                        </span>
                        <div class="partida-marcador">
                            <span class="<?= $p['ganador']===$p['jugador1']?'ganador':'' ?>">
                                <?= htmlspecialchars($p['jugador1']) ?>
                            </span>
                            <span class="marcador-badge">
                                <?= (int)$p['Juegos_Jugador1'] ?>–<?= (int)$p['Juegos_Jugador2'] ?>
                            </span>
                            <span class="<?= $p['ganador']===$p['jugador2']?'ganador':'' ?>">
                                <?= htmlspecialchars($p['jugador2']) ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>

            <section>
                <div class="section-header">
                    <h3 class="section-title">Próximas partidas</h3>
                </div>
                <?php if (empty($proximasPartidas)): ?>
                <div class="empty-state">
                    <span class="empty-icon">📅</span><p>Sin partidas programadas.</p>
                </div>
                <?php else: ?>
                <div class="partidas-list">
                    <?php foreach ($proximasPartidas as $p): ?>
                    <div class="partida-row">
                        <span class="partida-meta">
                            🏆 <?= htmlspecialchars($p['torneo']) ?>
                            <?= $p['ronda'] ? '· R'.(int)$p['ronda'] : '' ?>
                        </span>
                        <div class="partida-marcador">
                            <span><?= htmlspecialchars($p['jugador1']) ?></span>
                            <span class="vs-badge">VS</span>
                            <span><?= htmlspecialchars($p['jugador2']) ?></span>
                        </div>
                        <?php if ($p['Hora_Programada']): ?>
                        <span class="partida-hora">
                            📅 <?= htmlspecialchars((string)$p['Hora_Programada']) ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>

        </div>

    </div>
</div>

<style>
.db-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-top:.5rem;}
@media(max-width:860px){.db-grid{grid-template-columns:1fr;}}
.partidas-list{display:flex;flex-direction:column;gap:.5rem;}
.partida-row{background:var(--card);border:1px solid var(--border);border-radius:10px;
             padding:.75rem 1rem;transition:border-color .2s;}
.partida-row:hover{border-color:rgba(124,92,252,.35);}
.partida-meta{font-size:.7rem;color:var(--texto);display:block;margin-bottom:.3rem;}
.partida-marcador{display:flex;align-items:center;gap:.5rem;font-size:.85rem;
                  font-weight:600;color:var(--texto-claro);}
.partida-marcador .ganador{color:var(--blanco);font-weight:700;}
.marcador-badge{font-family:'Orbitron',sans-serif;font-size:.72rem;font-weight:900;
                color:var(--amarillo);padding:.1rem .4rem;
                background:rgba(245,197,24,.1);border-radius:6px;white-space:nowrap;}
.vs-badge{font-size:.65rem;font-weight:700;color:var(--texto);opacity:.5;}
.partida-hora{font-size:.7rem;color:var(--texto);display:block;margin-top:.2rem;}
</style>

<script>
document.addEventListener('click', function(e) {
    if (document.body.classList.contains('sidebar-open') &&
        !e.target.closest('.sidebar') && !e.target.closest('.sidebar-toggle'))
        document.body.classList.remove('sidebar-open');
});
</script>
</body>
</html>