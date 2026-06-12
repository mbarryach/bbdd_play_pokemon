<?php
// admin/includes/sidebar.php
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

    <div class="sidebar-profile">
        <div class="profile-avatar"><?= Auth::getIconoRol() ?></div>
        <div class="profile-info">
            <div class="profile-name"><?= htmlspecialchars(Auth::getUsuario()) ?></div>
            <div class="profile-role role-<?= $rol ?>"><?= Auth::getLabelRol() ?></div>
        </div>
    </div>

    <nav class="sidebar-nav">

        <div class="nav-section-title">Panel</div>
        <a href="<?= APP_URL ?>/admin/dashboard.php"
           class="nav-item <?= $paginaAdmin==='dashboard'?'active':'' ?>">
            <span class="nav-icon">◈</span> Dashboard
        </a>


        <?php if (Auth::tieneRol([ROL_ADMIN, ROL_ARBITRO])): ?>

        <div class="nav-section-title">Torneos</div>
        <a href="<?= APP_URL ?>/admin/views/torneos/index.php"
           class="nav-item <?= $paginaAdmin==='torneos'?'active':'' ?>">
            <span class="nav-icon">⚙️</span> Gestionar torneos
        </a>

        <div class="nav-section-title">Jugadores</div>
        <a href="<?= APP_URL ?>/admin/views/jugadores/index.php"
           class="nav-item <?= $paginaAdmin==='jugadores'?'active':'' ?>">
            <span class="nav-icon">🪪</span> Gestionar jugadores
        </a>
       
        <?php endif; ?>


        <div class="nav-section-title">Vista pública</div>
        <a href="<?= APP_URL ?>/views/clasification/index.php" class="nav-item" target="_blank">
            <span class="nav-icon">📈</span> Clasificación
        </a>
        <a href="<?= APP_URL ?>/views/torneos/index.php" class="nav-item" target="_blank">
            <span class="nav-icon">🏆</span> Torneos
        </a>
        <a href="<?= APP_URL ?>/views/jugadores/index.php" class="nav-item" target="_blank">
            <span class="nav-icon">🎴</span> Jugadores
        </a>
        <a href="<?= APP_URL ?>/views/resultado/index.php"
            class="nav-item <?= $paginaAdmin==='resultados'?'active':'' ?>">
            <span class="nav-icon">📊</span> Resultados
        </a>

        <?php if (Auth::esAdmin()): ?>
        <div class="nav-section-title">Administración</div>
        <a href="<?= APP_URL ?>/admin/views/usuarios/index.php"
           class="nav-item <?= $paginaAdmin==='usuarios'?'active':'' ?>">
            <span class="nav-icon">👥</span> Usuarios
        </a>
        <?php endif; ?>

    </nav>

    <div class="sidebar-footer">
        <a href="<?= APP_URL ?>/index.php" class="nav-item" target="_blank">
            <span class="nav-icon">🌐</span> Inicio del sitio
        </a>
        <a href="<?= APP_URL ?>/admin/logout.php" class="nav-item nav-logout">
            <span class="nav-icon">↩</span> Cerrar sesión
        </a>
    </div>

</aside>