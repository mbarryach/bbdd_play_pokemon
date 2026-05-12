<?php
// ─────────────────────────────────────────────────────────
//  admin/includes/sidebar.php
//  Se incluye en todas las páginas del panel de admin.
// ─────────────────────────────────────────────────────────
$paginaAdmin = $paginaAdmin ?? '';
$rol = Auth::getRol();
?>
<aside class="sidebar">

    <div class="sidebar-brand">
        <span class="sidebar-icon">⚡</span>
        <div>
            <div class="sidebar-title"><?= htmlspecialchars(APP_NAME) ?></div>
            <div class="sidebar-version">v<?= APP_VERSION ?></div>
        </div>
    </div>

    <!-- Perfil del usuario -->
    <div class="sidebar-profile">
        <div class="profile-avatar">
            <?= Auth::getIconoRol() ?>
        </div>
        <div class="profile-info">
            <div class="profile-name"><?= htmlspecialchars(Auth::getUsuario()) ?></div>
            <div class="profile-role role-<?= $rol ?>">
                <?= Auth::getLabelRol() ?>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">

        <!-- ── Sección principal ── -->
        <div class="nav-section-title">Principal</div>

        <a href="<?= APP_URL ?>/admin/dashboard.php"
           class="nav-item <?= $paginaAdmin === 'dashboard' ? 'active' : '' ?>">
            <span class="nav-icon">◈</span> Dashboard
        </a>

        <!-- ── Torneos ── -->
        <div class="nav-section-title">Torneos</div>

        <a href="<?= APP_URL ?>/admin/torneos.php"
           class="nav-item <?= $paginaAdmin === 'torneos' ? 'active' : '' ?>">
            <span class="nav-icon">🏆</span> Torneos
        </a>

        <?php if (Auth::tieneRol([ROL_ADMIN, ROL_ARBITRO])): ?>
        <a href="<?= APP_URL ?>/admin/partidos.php"
           class="nav-item <?= $paginaAdmin === 'partidos' ? 'active' : '' ?>">
            <span class="nav-icon">⚔️</span> Partidos
        </a>

        <a href="<?= APP_URL ?>/admin/resultados.php"
           class="nav-item <?= $paginaAdmin === 'resultados' ? 'active' : '' ?>">
            <span class="nav-icon">📊</span> Resultados
        </a>

        <a href="<?= APP_URL ?>/admin/inscripciones.php"
           class="nav-item <?= $paginaAdmin === 'inscripciones' ? 'active' : '' ?>">
            <span class="nav-icon">📋</span> Inscripciones
        </a>
        <?php endif; ?>

        <!-- ── Jugadores ── -->
        <div class="nav-section-title">Jugadores</div>

        <a href="<?= APP_URL ?>/admin/jugadores.php"
           class="nav-item <?= $paginaAdmin === 'jugadores' ? 'active' : '' ?>">
            <span class="nav-icon">🎴</span> Jugadores
        </a>

        <?php if (Auth::tieneRol([ROL_ADMIN, ROL_ARBITRO])): ?>
        <a href="<?= APP_URL ?>/admin/equipos.php"
           class="nav-item <?= $paginaAdmin === 'equipos' ? 'active' : '' ?>">
            <span class="nav-icon">🛡️</span> Equipos
        </a>
        <?php endif; ?>

        <!-- ── Admin only ── -->
        <?php if (Auth::esAdmin()): ?>
        <div class="nav-section-title">Administración</div>

        <a href="<?= APP_URL ?>/admin/usuarios.php"
           class="nav-item <?= $paginaAdmin === 'usuarios' ? 'active' : '' ?>">
            <span class="nav-icon">👥</span> Usuarios
        </a>

        <a href="<?= APP_URL ?>/admin/configuracion.php"
           class="nav-item <?= $paginaAdmin === 'configuracion' ? 'active' : '' ?>">
            <span class="nav-icon">⚙️</span> Configuración
        </a>
        <?php endif; ?>

    </nav>

    <div class="sidebar-footer">
        <a href="<?= APP_URL ?>/index.php" class="nav-item" target="_blank">
            <span class="nav-icon">🌐</span> Ver sitio web
        </a>
        <a href="<?= APP_URL ?>/admin/logout.php" class="nav-item nav-logout">
            <span class="nav-icon">↩</span> Cerrar sesión
        </a>
    </div>

</aside>
