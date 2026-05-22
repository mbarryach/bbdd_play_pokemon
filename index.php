<?php
// ─────────────────────────────────────────────────────────
//  index.php — Portada pública
// ─────────────────────────────────────────────────────────
require_once __DIR__ . '/config.php';

$pdo          = ConexionDB::getInstancia()->getConexion();
$paginaActiva = 'inicio';
$tituloPagina = APP_NAME . ' — Inicio';

// ── Próximas partidas (sin resultado aún) ──────────────
try {
    $stmt = $pdo->prepare(
        'SELECT torneo, ronda, jugador1, jugador2, Hora_Programada
         FROM   v_proximas_partidas
         LIMIT  5'
    );
    $stmt->execute();
    $proximas = $stmt->fetchAll();
} catch (PDOException) {
    $proximas = [];
}

// ── Últimos resultados ─────────────────────────────────
try {
    $stmt = $pdo->prepare(
        'SELECT torneo, ronda, jugador1, jugador2,
                Juegos_Jugador1, Juegos_Jugador2, ganador
         FROM   v_resultados
         LIMIT  5'
    );
    $stmt->execute();
    $resultados = $stmt->fetchAll();
} catch (PDOException) {
    $resultados = [];
}

include INC_PATH . '/header.php';
?>

<div class="hero">
    <span class="hero-badge">TEMPORADA 2025</span>
    <h1 class="hero-title">
        Bienvenido a la<br>
        <span><?= htmlspecialchars(APP_NAME) ?></span>
    </h1>
    <p class="hero-sub">La competición más emocionante de la región</p>
</div>

<div class="home-grid">

    <!-- Próximas partidas -->
    <section>
        <h2 class="section-title">Próximas partidas</h2>

        <?php if (empty($proximas)): ?>
            <p style="color:var(--texto);opacity:.6">No hay partidas programadas.</p>
        <?php else: ?>
            <div class="partidas-col">
                <?php foreach ($proximas as $p): ?>
                <div class="partido-card fade-in">
                    <div class="partido-equipo local">
                        <span class="nombre"><?= htmlspecialchars($p['jugador1']) ?></span>
                    </div>
                    <div class="partido-centro">
                        <span class="marcador pendiente">VS</span>
                        <?php if ($p['Hora_Programada']): ?>
                        <span class="partido-fecha"><?= htmlspecialchars((string)$p['Hora_Programada']) ?></span>
                        <?php endif; ?>
                        <?php if ($p['ronda']): ?>
                        <span class="partido-ronda">Ronda <?= (int)$p['ronda'] ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="partido-equipo visitante">
                        <span class="nombre"><?= htmlspecialchars($p['jugador2']) ?></span>
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
            <p style="color:var(--texto);opacity:.6">No hay resultados todavía.</p>
        <?php else: ?>
            <div class="partidas-col">
                <?php foreach ($resultados as $p): ?>
                <div class="partido-card fade-in">
                    <div class="partido-equipo local">
                        <span class="nombre <?= $p['ganador']===$p['jugador1']?'ganador':'' ?>">
                            <?= htmlspecialchars($p['jugador1']) ?>
                        </span>
                    </div>
                    <div class="partido-centro">
                        <span class="marcador">
                            <?= (int)$p['Juegos_Jugador1'] ?> – <?= (int)$p['Juegos_Jugador2'] ?>
                        </span>
                        <?php if ($p['ronda']): ?>
                        <span class="partido-ronda">Ronda <?= (int)$p['ronda'] ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="partido-equipo visitante">
                        <span class="nombre <?= $p['ganador']===$p['jugador2']?'ganador':'' ?>">
                            <?= htmlspecialchars($p['jugador2']) ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

</div>

<div class="home-ctas">
    <a href="<?= APP_URL ?>/clasificacion.php" class="cta-btn">Ver clasificación →</a>
    <a href="<?= APP_URL ?>/torneos.php"       class="cta-btn cta-ghost">Ver torneos</a>
</div>

<style>
.home-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-top: 2rem;
}
@media(max-width:680px){ .home-grid{grid-template-columns:1fr;} }

.partidas-col { display: flex; flex-direction: column; gap: 1rem; }

/* Nombre ganador destacado */
.partido-equipo .nombre.ganador { color: var(--amarillo); font-weight: 900; }

.home-ctas {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2.5rem;
    flex-wrap: wrap;
}
.cta-btn {
    padding: .75rem 1.75rem;
    background: var(--purpura);
    color: #fff;
    border-radius: 10px;
    font-weight: 700;
    font-size: .9rem;
    transition: background .15s, transform .15s;
}
.cta-btn:hover { background: #6a4de0; transform: translateY(-2px); }
.cta-ghost {
    background: transparent;
    border: 1px solid var(--border);
    color: var(--texto-claro);
}
.cta-ghost:hover { background: rgba(255,255,255,.05); }
</style>

<?php include INC_PATH . '/footer.php'; ?>