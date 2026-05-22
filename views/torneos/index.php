<?php include INC_PATH . '/header.php'; ?>
<div class="page-header">
    <h1 class="page-heading">Torneos</h1>
    <p class="page-sub"><?= count($torneos) ?> torneo<?= count($torneos)!==1?'s':'' ?> registrados</p>
</div>
<?php if (empty($torneos)): ?>
<div class="empty-state"><span class="empty-icon">🏆</span><p>No hay torneos aún.</p></div>
<?php else: ?>
<div class="filtro-estados">
    <button class="f-btn active" data-e="todos">Todos</button>
    <button class="f-btn" data-e="En curso">En curso</button>
    <button class="f-btn" data-e="Pendiente">Pendiente</button>
    <button class="f-btn" data-e="Finalizado">Finalizado</button>
</div>
<div class="torneos-grid">
<?php foreach ($torneos as $t): ?>
<div class="torneo-card" data-estado="<?= htmlspecialchars($t['estado']) ?>">
    <div class="t-head">
        <div>
            <h3 class="t-nombre"><?= htmlspecialchars($t['Nombre']) ?></h3>
            <?php if ($t['Tipo_Torneo']): ?><span class="t-tipo">🎴 <?= htmlspecialchars($t['Tipo_Torneo']) ?></span><?php endif; ?>
        </div>
        <span class="estado-b e-<?= strtolower(str_replace(' ','_',$t['estado'])) ?>"><?= htmlspecialchars($t['estado']) ?></span>
    </div>
    <div class="t-detalles">
        <?php if ($t['Fecha_Inicio']): ?>
        <div class="d-item"><span>📅</span>
            <span><?= htmlspecialchars((string)$t['Fecha_Inicio']) ?><?= ($t['Fecha_Fin']&&$t['Fecha_Fin']!==$t['Fecha_Inicio'])?' → '.htmlspecialchars((string)$t['Fecha_Fin']):'' ?></span>
        </div>
        <?php endif; ?>
        <?php if ($t['Ubicacion']): ?>
        <div class="d-item"><span>📍</span>
            <span><?= htmlspecialchars($t['Ubicacion']) ?><?= $t['Pais']?', '.htmlspecialchars($t['Pais']):'' ?></span>
        </div>
        <?php endif; ?>
        <?php if ($t['temporada']): ?>
        <div class="d-item"><span>📆</span><span>Temporada <?= htmlspecialchars((string)$t['temporada']) ?></span></div>
        <?php endif; ?>
    </div>
    <div class="t-stats">
        <div class="ts"><span class="ts-n"><?= (int)$t['total_inscritos'] ?></span><span class="ts-l">Jugadores</span></div>
        <div class="ts"><span class="ts-n"><?= (int)$t['total_partidas'] ?></span><span class="ts-l">Partidas</span></div>
        <div class="ts"><span class="ts-n"><?= (int)$t['partidas_jugadas'] ?></span><span class="ts-l">Jugadas</span></div>
        <?php if ($t['Num_Rondas_Suizas']): ?><div class="ts"><span class="ts-n"><?= (int)$t['Num_Rondas_Suizas'] ?></span><span class="ts-l">Rondas</span></div><?php endif; ?>
        <?php if ($t['Tamanio_Top_Cut']): ?><div class="ts"><span class="ts-n">Top <?= (int)$t['Tamanio_Top_Cut'] ?></span><span class="ts-l">Top Cut</span></div><?php endif; ?>
    </div>
    <a href="<?= APP_URL ?>/clasificacion.php?torneo=<?= (int)$t['ID_Torneo'] ?>" class="t-link">Ver clasificación →</a>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
<style>
.page-header{margin:2rem 0 1.5rem}.page-heading{font-family:'Orbitron',sans-serif;font-size:1.4rem;font-weight:900;color:var(--amarillo)}.page-sub{color:var(--texto);font-size:.85rem;margin-top:.3rem}
.filtro-estados{display:flex;gap:.5rem;margin-bottom:1.25rem;flex-wrap:wrap}
.f-btn{padding:.35rem .85rem;border-radius:20px;font-size:.78rem;font-weight:700;border:1px solid var(--border);background:var(--card);color:var(--texto);cursor:pointer;transition:all .15s}
.f-btn:hover{border-color:var(--purpura);color:var(--blanco)}.f-btn.active{background:var(--purpura);border-color:var(--purpura);color:#fff}
.torneos-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.1rem}
.torneo-card{background:var(--card);border:1px solid var(--border);border-radius:16px;padding:1.25rem;display:flex;flex-direction:column;gap:.85rem;transition:border-color .2s,transform .2s}
.torneo-card:hover{border-color:rgba(124,92,252,.4);transform:translateY(-2px)}.torneo-card.oculto{display:none}
.t-head{display:flex;justify-content:space-between;align-items:flex-start;gap:.75rem}
.t-nombre{font-size:1rem;font-weight:700;color:var(--blanco);line-height:1.3;margin:0}
.t-tipo{font-size:.72rem;color:var(--texto);display:block;margin-top:.25rem}
.estado-b{display:inline-block;padding:.25rem .7rem;border-radius:12px;font-size:.68rem;font-weight:700;white-space:nowrap;flex-shrink:0}
.e-en_curso{background:rgba(6,214,160,.12);color:var(--verde);border:1px solid rgba(6,214,160,.3)}
.e-finalizado{background:rgba(176,176,200,.08);color:var(--texto);border:1px solid var(--border)}
.e-pendiente{background:rgba(245,197,24,.1);color:var(--amarillo);border:1px solid rgba(245,197,24,.3)}
.t-detalles{display:flex;flex-direction:column;gap:.3rem}
.d-item{display:flex;align-items:baseline;gap:.4rem;font-size:.8rem;color:var(--texto-claro)}
.t-stats{display:flex;gap:.75rem;flex-wrap:wrap;padding:.75rem 0;border-top:1px solid var(--border)}
.ts{text-align:center;min-width:48px}
.ts-n{display:block;font-family:'Orbitron',sans-serif;font-size:.85rem;font-weight:900;color:var(--amarillo)}
.ts-l{display:block;font-size:.62rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--texto);margin-top:.15rem}
.t-link{display:block;text-align:center;padding:.55rem;background:rgba(124,92,252,.1);border:1px solid rgba(124,92,252,.25);border-radius:10px;color:#a78bfa;font-size:.82rem;font-weight:700;transition:background .15s}
.t-link:hover{background:rgba(124,92,252,.2)}
.empty-state{text-align:center;padding:3rem;background:var(--card);border:1px dashed var(--border);border-radius:14px}
.empty-icon{font-size:2.5rem;display:block;opacity:.4;margin-bottom:.75rem}
</style>
<script>
document.querySelectorAll('.f-btn').forEach(b=>{b.addEventListener('click',function(){document.querySelectorAll('.f-btn').forEach(x=>x.classList.remove('active'));this.classList.add('active');const e=this.dataset.e;document.querySelectorAll('.torneo-card').forEach(c=>{c.classList.toggle('oculto',e!=='todos'&&c.dataset.estado!==e);});});});
</script>
<?php include INC_PATH . '/footer.php'; ?>
