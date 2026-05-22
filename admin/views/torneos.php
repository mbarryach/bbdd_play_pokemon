<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin.css">
</head>
<body>

<?php include ADMIN_PATH . '/includes/sidebar.php'; ?>

<div class="admin-main">

    <header class="topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle" onclick="document.body.classList.toggle('sidebar-open')">☰</button>
            <h1 class="page-title">Gestión de torneos</h1>
        </div>
        <div class="topbar-right">
            <?php if (Auth::esAdmin()): ?>
            <a href="<?= APP_URL ?>/admin/torneos.php?accion=crear" class="btn btn-primary btn-sm">
                + Nuevo torneo
            </a>
            <?php endif; ?>
            <span class="topbar-user">
                <?= Auth::getIconoRol() ?>
                <strong><?= htmlspecialchars(Auth::getUsuario()) ?></strong>
                <span class="badge badge-<?= Auth::getRol() ?>"><?= Auth::getLabelRol() ?></span>
            </span>
        </div>
    </header>

    <div class="admin-content">

        <!-- Mensajes de feedback -->
        <?php
        $feedbacks = [
            'creado'   => ['success', '✓ Torneo creado correctamente.'],
            'editado'  => ['success', '✓ Torneo actualizado.'],
            'eliminado'=> ['success', '✓ Torneo eliminado.'],
        ];
        if (isset($feedbacks[$msg])): ?>
        <div class="alert alert-<?= $feedbacks[$msg][0] ?>"><?= $feedbacks[$msg][1] ?></div>
        <?php endif; ?>

        <!-- Stats rápidas -->
        <div class="stats-grid" style="margin-bottom:1.5rem">
            <?php
            $enCurso    = count(array_filter($torneos, fn($t)=>$t['estado']==='En curso'));
            $pendientes = count(array_filter($torneos, fn($t)=>$t['estado']==='Pendiente'));
            $finalizados= count(array_filter($torneos, fn($t)=>$t['estado']==='Finalizado'));
            ?>
            <div class="stat-card">
                <div class="stat-icon">🏆</div>
                <div class="stat-body">
                    <div class="stat-value"><?= count($torneos) ?></div>
                    <div class="stat-label">Total torneos</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color:var(--verde)">●</div>
                <div class="stat-body">
                    <div class="stat-value"><?= $enCurso ?></div>
                    <div class="stat-label">En curso</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color:var(--amarillo)">◌</div>
                <div class="stat-body">
                    <div class="stat-value"><?= $pendientes ?></div>
                    <div class="stat-label">Pendientes</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="opacity:.5">✓</div>
                <div class="stat-body">
                    <div class="stat-value"><?= $finalizados ?></div>
                    <div class="stat-label">Finalizados</div>
                </div>
            </div>
        </div>

        <div class="section-header">
            <h3 class="section-title">Todos los torneos</h3>
        </div>

        <?php if (empty($torneos)): ?>
        <div class="empty-state">
            <span class="empty-icon">🏆</span>
            <p>No hay torneos registrados.</p>
            <?php if (Auth::esAdmin()): ?>
            <a href="<?= APP_URL ?>/admin/torneos.php?accion=crear" class="btn btn-primary btn-sm">
                + Crear el primer torneo
            </a>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="text-align:left">Torneo</th>
                        <th>Tipo</th>
                        <th>Fechas</th>
                        <th>Estado</th>
                        <th>Jugadores</th>
                        <th>Partidas</th>
                        <th>Rondas</th>
                        <?php if (Auth::esAdmin()): ?><th>Acciones</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($torneos as $t): ?>
                <tr>
                    <td style="text-align:left">
                        <div class="t-nombre-tabla"><?= htmlspecialchars($t['Nombre']) ?></div>
                        <?php if ($t['Ubicacion']): ?>
                            <div class="t-ubicacion">📍 <?= htmlspecialchars($t['Ubicacion']) ?><?= $t['Pais']?', '.htmlspecialchars($t['Pais']):'' ?></div>
                        <?php endif; ?>
                        <?php if ($t['temporada']): ?>
                            <div class="t-temporada">Temporada <?= htmlspecialchars((string)$t['temporada']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:.8rem;color:var(--texto)">
                        <?= htmlspecialchars($t['Tipo_Torneo'] ?? '—') ?>
                    </td>
                    <td style="font-size:.78rem;color:var(--texto);white-space:nowrap">
                        <?php if ($t['Fecha_Inicio']): ?>
                            <?= htmlspecialchars((string)$t['Fecha_Inicio']) ?>
                            <?= $t['Fecha_Fin'] ? '<br>→ '.htmlspecialchars((string)$t['Fecha_Fin']) : '' ?>
                        <?php else: ?>
                            <span style="opacity:.4">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge badge-estado-<?= strtolower(str_replace(' ','_',$t['estado'])) ?>">
                            <?= htmlspecialchars($t['estado']) ?>
                        </span>
                    </td>
                    <td><?= (int)$t['total_inscritos'] ?></td>
                    <td>
                        <span style="color:var(--amarillo);font-weight:700"><?= (int)$t['partidas_jugadas'] ?></span>
                        <span style="color:var(--texto);font-size:.75rem"> / <?= (int)$t['total_partidas'] ?></span>
                    </td>
                    <td style="font-size:.82rem">
                        <?= $t['Num_Rondas_Suizas'] ? (int)$t['Num_Rondas_Suizas'].' R' : '—' ?>
                        <?= $t['Tamanio_Top_Cut']   ? ' + Top'.(int)$t['Tamanio_Top_Cut'] : '' ?>
                    </td>
                    <?php if (Auth::esAdmin()): ?>
                    <td class="td-acciones">
                        <a href="<?= APP_URL ?>/clasificacion.php?torneo=<?= (int)$t['ID_Torneo'] ?>"
                           class="btn btn-ghost btn-sm" target="_blank">Ver</a>
                        <a href="<?= APP_URL ?>/admin/torneos.php?accion=editar&id=<?= (int)$t['ID_Torneo'] ?>"
                           class="btn btn-warning btn-sm">Editar</a>
                        <a href="<?= APP_URL ?>/admin/torneos.php?accion=eliminar&id=<?= (int)$t['ID_Torneo'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('¿Eliminar el torneo «<?= htmlspecialchars(addslashes($t['Nombre'])) ?>»? Esta acción no se puede deshacer.')">
                           Eliminar
                        </a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

    </div>
</div>

<style>
.t-nombre-tabla { font-weight:700; color:var(--blanco); font-size:.9rem; }
.t-ubicacion    { font-size:.72rem; color:var(--texto); margin-top:.2rem; }
.t-temporada    { font-size:.7rem; color:var(--texto); opacity:.6; margin-top:.1rem; }

.badge-estado-en_curso   { background:rgba(6,214,160,.12); color:var(--verde);    border:1px solid rgba(6,214,160,.3);  padding:.2rem .6rem; border-radius:10px; font-size:.7rem; font-weight:700; }
.badge-estado-finalizado { background:rgba(176,176,200,.08); color:var(--texto);  border:1px solid var(--border);       padding:.2rem .6rem; border-radius:10px; font-size:.7rem; font-weight:700; }
.badge-estado-pendiente  { background:rgba(245,197,24,.1); color:var(--amarillo); border:1px solid rgba(245,197,24,.3); padding:.2rem .6rem; border-radius:10px; font-size:.7rem; font-weight:700; }
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
