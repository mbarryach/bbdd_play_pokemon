<?php
// ─────────────────────────────────────────────────────────
//  index.php  —  Portada pública
// ─────────────────────────────────────────────────────────

require_once __DIR__ . '/config.php';

$pdo          = ConexionDB::getInstancia()->getConexion();
$paginaActiva = 'inicio';
$tituloPagina = APP_NAME . ' — Inicio';

// ── Próximos partidos (no jugados) ─────────────────────
try {
    $stmt = $pdo->prepare('CALL sp_proximos_partidos(5)');
    $stmt->execute();
    $proximos = $stmt->fetchAll();
} catch (PDOException $e) {
    $proximos = [];
}

// ── Últimos resultados (jugados) ───────────────────────
try {
    $stmt = $pdo->prepare('CALL sp_ultimos_resultados(5)');
    $stmt->execute();
    $resultados = $stmt->fetchAll();
} catch (PDOException $e) {
    $resultados = [];
}

include INC_PATH . '/header.php';
?>

<!-- Hero -->
<div class="hero">
    <span class="hero-badge">TEMPORADA 2025</span>
    <h1 class="hero-title">
        Bienvenido a la<br>
        <span><?= htmlspecialchars(APP_NAME) ?></span>
    </h1>
    <p class="hero-sub">La competición más emocionante de la región</p>
</div>

<!-- Grid: Próximos + Últimos resultados -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-top:2rem;">

    <!-- Próximos partidos -->
    <section>
        <h2 class="section-title">Próximos partidos</h2>

        <?php if (empty($proximos)): ?>
            <p style="color:var(--texto);opacity:.6;">No hay partidos programados.</p>
        <?php else: ?>
            <div style="display:flex;flex-direction:column;gap:1rem;">
                <?php foreach ($proximos as $p): ?>
                <div class="partido-card fade-in">
                    <div class="partido-equipo local">
                        <span class="nombre"><?= htmlspecialchars($p['equipo_local']) ?></span>
                    </div>
                    <div class="partido-centro">
                        <span class="marcador pendiente">VS</span>
                        <span class="partido-fecha"><?= htmlspecialchars($p['fecha']) ?></span>
                        <span class="partido-ronda"><?= htmlspecialchars($p['ronda']) ?></span>
                    </div>
                    <div class="partido-equipo visitante">
                        <span class="nombre"><?= htmlspecialchars($p['equipo_visitante']) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Últimos resultados -->
    <section>
        <h2 class="section-title">Últimos resultados</h2>

        <?php if (empty($resultados)): ?>
            <p style="color:var(--texto);opacity:.6;">No hay resultados todavía.</p>
        <?php else: ?>
            <div style="display:flex;flex-direction:column;gap:1rem;">
                <?php foreach ($resultados as $p): ?>
                <div class="partido-card fade-in">
                    <div class="partido-equipo local">
                        <span class="nombre"><?= htmlspecialchars($p['equipo_local']) ?></span>
                    </div>
                    <div class="partido-centro">
                        <span class="marcador">
                            <?= (int)$p['goles_local'] ?> – <?= (int)$p['goles_visitante'] ?>
                        </span>
                        <span class="partido-fecha"><?= htmlspecialchars($p['fecha']) ?></span>
                    </div>
                    <div class="partido-equipo visitante">
                        <span class="nombre"><?= htmlspecialchars($p['equipo_visitante']) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

</div>

<?php include INC_PATH . '/footer.php'; ?>
