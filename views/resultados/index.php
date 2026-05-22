<?php include INC_PATH . '/header.php'; ?>
<div class="page-header">
    <h1 class="page-heading">Resultados</h1>
    <p class="page-sub"><?= (int)$totalItems ?> partidas registradas</p>
</div>
<?php if (!empty($torneos)): ?>
<form method="GET" class="selector-form">
    <label class="selector-label">Filtrar por torneo</label>
    <select name="torneo" class="selector-select" onchange="this.form.submit()">
        <option value="0">— Todos los torneos —</option>
        <?php foreach ($torneos as $t): ?>
        <option value="<?= (int)$t['ID_Torneo'] ?>"
            <?= (isset($torneoActual['ID_Torneo'])&&(int)$t['ID_Torneo']===(int)$torneoActual['ID_Torneo'])?'selected':'' ?>>
            <?= htmlspecialchars($t['Nombre']) ?>
        </option>
        <?php endforeach; ?>
    </select>
</form>
<?php endif; ?>
<?php if (empty($resultados)): ?>
<div class="empty-state"><span class="empty-icon">⚔️</span><p>No hay resultados aún.</p></div>
<?php else: ?>
<div class="resultados-grid">
<?php foreach ($resultados as $r): ?>
<div class="resultado-card">
    <div class="resultado-header">
        <span class="r-torneo">🏆 <?= htmlspecialchars($r['torneo']) ?></span>
        <span class="r-meta"><?= $r['ronda']?'R'.(int)$r['ronda']:'' ?><?= $r['fase']?' · '.htmlspecialchars($r['fase']):'' ?><?= $r['mesa']?' · Mesa '.(int)$r['mesa']:'' ?></span>
    </div>
    <div class="resultado-cuerpo">
        <div class="rjugador <?= $r['ganador']===$r['jugador1']?'ganador':'' ?>">
            <span class="rj-nombre"><?= htmlspecialchars($r['jugador1']) ?></span>
            <?php if ($r['player1_id']): ?><span class="rj-pid"><?= htmlspecialchars($r['player1_id']) ?></span><?php endif; ?>
        </div>
        <div class="marcador-bloque">
            <span class="marc-n"><?= (int)$r['Juegos_Jugador1'] ?></span>
            <span class="marc-sep">—</span>
            <span class="marc-n"><?= (int)$r['Juegos_Jugador2'] ?></span>
        </div>
        <div class="rjugador derecha <?= $r['ganador']===$r['jugador2']?'ganador':'' ?>">
            <span class="rj-nombre"><?= htmlspecialchars($r['jugador2']) ?></span>
            <?php if ($r['player2_id']): ?><span class="rj-pid"><?= htmlspecialchars($r['player2_id']) ?></span><?php endif; ?>
        </div>
    </div>
    <div class="resultado-pie">
        <?php if ($r['ganador']==='Empate'): ?><span class="badge-empate">🤝 Empate</span>
        <?php else: ?><span class="badge-ganador">🏆 <?= htmlspecialchars($r['ganador']) ?></span><?php endif; ?>
        <div style="display:flex;gap:.5rem;align-items:center;">
            <?php if ($r['Verificado']): ?><span class="badge-verificado">✓ Verificado</span><?php endif; ?>
            <?php if ($r['Hora_Finalizacion']): ?><span class="r-hora">📅 <?= htmlspecialchars((string)$r['Hora_Finalizacion']) ?></span><?php endif; ?>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>
<?php if ($totalPaginas > 1): ?>
<nav class="paginacion">
    <?php if ($pagina > 1): ?><a href="?torneo=<?= $torneoActual['ID_Torneo']??0 ?>&p=<?= $pagina-1 ?>" class="page-link">← Ant.</a><?php endif; ?>
    <?php for ($i=max(1,$pagina-2);$i<=min($totalPaginas,$pagina+2);$i++): ?>
    <a href="?torneo=<?= $torneoActual['ID_Torneo']??0 ?>&p=<?= $i ?>" class="page-link <?= $i===$pagina?'active':'' ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($pagina < $totalPaginas): ?><a href="?torneo=<?= $torneoActual['ID_Torneo']??0 ?>&p=<?= $pagina+1 ?>" class="page-link">Sig. →</a><?php endif; ?>
</nav>
<?php endif; ?>
<?php endif; ?>
<style>
.page-header{margin:2rem 0 1.25rem;}.page-heading{font-family:'Orbitron',sans-serif;font-size:1.4rem;font-weight:900;color:var(--amarillo);}.page-sub{color:var(--texto);font-size:.85rem;margin-top:.3rem;}
.selector-form{margin-bottom:1.5rem;}.selector-label{display:block;font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--texto);margin-bottom:.4rem;}
.selector-select{background:var(--card);border:1px solid var(--border);border-radius:10px;padding:.6rem 2.25rem .6rem .9rem;color:var(--blanco);font-family:'Nunito',sans-serif;font-size:.9rem;appearance:none;outline:none;cursor:pointer;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath fill='%238888a8' d='M1 1l5 5 5-5'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right .75rem center;transition:border-color .2s;}.selector-select:focus{border-color:var(--purpura);}
.resultados-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1rem;}
.resultado-card{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:1rem 1.2rem;display:flex;flex-direction:column;gap:.7rem;transition:border-color .2s,transform .2s;}
.resultado-card:hover{border-color:rgba(124,92,252,.4);transform:translateY(-2px);}
.resultado-header{display:flex;justify-content:space-between;align-items:center;font-size:.72rem;color:var(--texto);}
.r-torneo{font-weight:700;}.r-meta{opacity:.7;font-size:.68rem;}
.resultado-cuerpo{display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:.6rem;}
.rjugador{display:flex;flex-direction:column;}.rjugador.derecha{text-align:right;align-items:flex-end;}
.rj-nombre{font-weight:700;font-size:.88rem;color:var(--texto-claro);}.rj-pid{font-size:.68rem;color:var(--texto);font-family:monospace;}
.rjugador.ganador .rj-nombre{color:var(--blanco);}
.marcador-bloque{display:flex;align-items:center;gap:.3rem;justify-content:center;white-space:nowrap;}
.marc-n{font-family:'Orbitron',sans-serif;font-size:1.1rem;font-weight:900;color:var(--amarillo);}.marc-sep{font-size:.8rem;color:var(--texto);opacity:.4;}
.resultado-pie{display:flex;justify-content:space-between;align-items:center;font-size:.74rem;border-top:1px solid var(--border);padding-top:.6rem;flex-wrap:wrap;gap:.3rem;}
.badge-ganador{color:var(--amarillo);font-weight:700;}.badge-empate{color:var(--texto);}
.badge-verificado{color:var(--verde);font-size:.7rem;font-weight:700;}.r-hora{color:var(--texto);opacity:.7;}
.paginacion{display:flex;justify-content:center;gap:.4rem;margin-top:1.5rem;flex-wrap:wrap;}
.page-link{padding:.45rem .85rem;border-radius:8px;font-size:.82rem;font-weight:700;color:var(--texto-claro);background:var(--card);border:1px solid var(--border);transition:all .15s;}
.page-link:hover,.page-link.active{background:rgba(124,92,252,.15);border-color:var(--purpura);color:var(--purpura);}
.empty-state{text-align:center;padding:3rem;background:var(--card);border:1px dashed var(--border);border-radius:14px;}.empty-icon{font-size:2.5rem;display:block;opacity:.4;margin-bottom:.75rem;}
</style>
<?php include INC_PATH . '/footer.php'; ?>
