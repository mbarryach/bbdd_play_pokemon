<?php include INC_PATH . '/header.php'; ?>

<div class="page-header">
    <h1 class="page-heading">Resultados</h1>
    <p class="page-sub"><?= $totalItems ?> partidos disputados</p>
</div>

<?php if (empty($resultados)): ?>
    <div class="empty-state">
        <span class="empty-icon">⚔️</span>
        <p>Aún no hay resultados registrados.</p>
    </div>
<?php else: ?>

<div class="resultados-grid">
    <?php foreach ($resultados as $r): ?>
    <div class="resultado-card fade-in">
        <div class="resultado-ronda"><?= htmlspecialchars($r['ronda'] ?? '') ?></div>

        <div class="resultado-equipos">
            <div class="resultado-equipo <?= $r['ganador'] === $r['equipo_local'] ? 'ganador' : '' ?>">
                <?= htmlspecialchars($r['equipo_local']) ?>
            </div>

            <div class="resultado-marcador">
                <span class="marcador-num"><?= (int)$r['goles_local'] ?></span>
                <span class="marcador-sep">—</span>
                <span class="marcador-num"><?= (int)$r['goles_visitante'] ?></span>
            </div>

            <div class="resultado-equipo derecha <?= $r['ganador'] === $r['equipo_visitante'] ? 'ganador' : '' ?>">
                <?= htmlspecialchars($r['equipo_visitante']) ?>
            </div>
        </div>

        <div class="resultado-meta">
            <span class="resultado-fecha">📅 <?= htmlspecialchars($r['fecha']) ?></span>
            <?php if ($r['ganador'] !== 'Empate'): ?>
                <span class="resultado-ganador">🏆 <?= htmlspecialchars($r['ganador']) ?></span>
            <?php else: ?>
                <span class="resultado-empate">🤝 Empate</span>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Paginación -->
<?php if ($totalPaginas > 1): ?>
<div class="paginacion">
    <?php if ($pagina > 1): ?>
        <a href="?p=<?= $pagina - 1 ?>" class="page-link">← Anterior</a>
    <?php endif; ?>

    <?php for ($i = max(1, $pagina - 2); $i <= min($totalPaginas, $pagina + 2); $i++): ?>
        <a href="?p=<?= $i ?>" class="page-link <?= $i === $pagina ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>

    <?php if ($pagina < $totalPaginas): ?>
        <a href="?p=<?= $pagina + 1 ?>" class="page-link">Siguiente →</a>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php endif; ?>

<style>
.page-header  { margin: 2rem 0 1.5rem; }
.page-heading { font-family: 'Orbitron', sans-serif; font-size: 1.4rem; font-weight: 900; color: var(--amarillo); }
.page-sub     { color: var(--texto); font-size: .85rem; margin-top: .3rem; }

.resultados-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1rem; }

.resultado-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 1.1rem 1.25rem;
    transition: border-color .2s, transform .2s;
}
.resultado-card:hover { border-color: rgba(124,92,252,.4); transform: translateY(-2px); }

.resultado-ronda {
    font-size: .65rem; font-weight: 700; letter-spacing: .14em;
    color: var(--purpura); text-transform: uppercase; margin-bottom: .6rem;
    min-height: 1rem;
}

.resultado-equipos {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    gap: .75rem;
    margin-bottom: .75rem;
}

.resultado-equipo {
    font-weight: 700;
    font-size: .9rem;
    color: var(--texto-claro);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.resultado-equipo.ganador { color: var(--blanco); }
.resultado-equipo.derecha { text-align: right; }

.resultado-marcador {
    display: flex;
    align-items: center;
    gap: .3rem;
    white-space: nowrap;
}
.marcador-num { font-family: 'Orbitron', sans-serif; font-size: 1.2rem; font-weight: 900; color: var(--amarillo); }
.marcador-sep { font-size: .8rem; color: var(--texto); opacity: .5; }

.resultado-meta {
    display: flex;
    justify-content: space-between;
    font-size: .75rem;
    color: var(--texto);
    border-top: 1px solid var(--border);
    padding-top: .6rem;
    flex-wrap: wrap;
    gap: .3rem;
}
.resultado-ganador { color: var(--amarillo); font-weight: 700; }
.resultado-empate  { color: var(--texto); }

.paginacion { display: flex; justify-content: center; gap: .4rem; margin-top: 1.5rem; flex-wrap: wrap; }
.page-link {
    padding: .45rem .85rem;
    border-radius: 8px;
    font-size: .82rem;
    font-weight: 700;
    color: var(--texto-claro);
    background: var(--card);
    border: 1px solid var(--border);
    transition: all .15s;
}
.page-link:hover, .page-link.active {
    background: rgba(124,92,252,.15);
    border-color: var(--purpura);
    color: var(--purpura);
}

.empty-state { text-align: center; padding: 3rem; background: var(--card);
               border: 1px dashed var(--border); border-radius: 14px; }
.empty-icon  { font-size: 2.5rem; display: block; opacity: .4; margin-bottom: .75rem; }

.fade-in { animation: fadeIn .35s ease both; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }
</style>

<?php include INC_PATH . '/footer.php'; ?>
