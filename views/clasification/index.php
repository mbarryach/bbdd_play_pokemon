<?php include INC_PATH . '/header.php'; ?>

<div class="page-header">
    <div>
        <h1 class="page-heading">Clasificación</h1>
        <p class="page-sub">
            <?= $torneoActual
                ? htmlspecialchars($torneoActual['Nombre']) . ' · ' . $torneoActual['total_inscritos'] . ' jugadores'
                : 'Selecciona un torneo' ?>
        </p>
    </div>
</div>

<!-- Selector de torneo -->
<?php if (!empty($torneos)): ?>
<form method="GET" class="selector-form">
    <label class="selector-label">Torneo</label>
    <select name="torneo" class="selector-select" onchange="this.form.submit()">
        <?php foreach ($torneos as $t): ?>
        <option value="<?= (int)$t['ID_Torneo'] ?>"
                <?= (isset($torneoActual['ID_Torneo']) && (int)$t['ID_Torneo']===(int)$torneoActual['ID_Torneo']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($t['Nombre']) ?>
            (<?= htmlspecialchars($t['estado']) ?>)
        </option>
        <?php endforeach; ?>
    </select>
</form>
<?php endif; ?>

<!-- Tabla de clasificación -->
<?php if (empty($tabla)): ?>
<div class="empty-state">
    <span class="empty-icon">🏆</span>
    <p><?= $torneoActual ? 'Aún no hay clasificación para este torneo.' : 'No hay torneos disponibles.' ?></p>
</div>
<?php else: ?>

<!-- Info del torneo seleccionado -->
<?php if ($torneoActual): ?>
<div class="torneo-info-bar">
    <?php if ($torneoActual['Ubicacion']): ?>
        <span>📍 <?= htmlspecialchars($torneoActual['Ubicacion']) ?></span>
    <?php endif; ?>
    <?php if ($torneoActual['Fecha_Inicio']): ?>
        <span>📅 <?= htmlspecialchars((string)$torneoActual['Fecha_Inicio']) ?></span>
    <?php endif; ?>
    <?php if ($torneoActual['Tipo_Torneo']): ?>
        <span>🎴 <?= htmlspecialchars($torneoActual['Tipo_Torneo']) ?></span>
    <?php endif; ?>
    <?php if ($torneoActual['Num_Rondas_Suizas']): ?>
        <span>⚔️ <?= (int)$torneoActual['Num_Rondas_Suizas'] ?> rondas</span>
    <?php endif; ?>
    <span class="estado-badge estado-<?= strtolower(str_replace(' ','_',$torneoActual['estado'])) ?>">
        <?= htmlspecialchars($torneoActual['estado']) ?>
    </span>
</div>
<?php endif; ?>

<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th style="text-align:left">Jugador</th>
                <th title="División">DIV</th>
                <th title="Partidas jugadas">PJ</th>
                <th title="Victorias" class="col-v">V</th>
                <th title="Empates">E</th>
                <th title="Derrotas" class="col-d">D</th>
                <th title="Puntos">Pts</th>
                <th title="Opponent Match Win %" class="col-pct hide-sm">OMW%</th>
                <th title="Player Match Win %" class="col-pct hide-sm">PMW%</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($tabla as $pos => $f):
            $posDisplay = $f['posicion_final'] ?? ($pos + 1);
        ?>
        <tr class="<?= $pos===0?'fila-lider':'' ?>">
            <td class="col-pos">
                <?php if ($posDisplay==1):     ?><span class="medalla oro">1</span>
                <?php elseif ($posDisplay==2): ?><span class="medalla plata">2</span>
                <?php elseif ($posDisplay==3): ?><span class="medalla bronce">3</span>
                <?php else:                    ?><span class="pos-num"><?= (int)$posDisplay ?></span>
                <?php endif; ?>
            </td>
            <td class="col-jugador">
                <span class="j-nombre"><?= htmlspecialchars($f['jugador']) ?></span>
                <?php if ($f['Player_ID']): ?>
                    <span class="j-pid"><?= htmlspecialchars($f['Player_ID']) ?></span>
                <?php endif; ?>
            </td>
            <td class="col-div"><?= htmlspecialchars($f['Division'] ?? '—') ?></td>
            <td class="col-num"><?= (int)$f['partidas_jugadas'] ?></td>
            <td class="col-num col-v"><?= (int)$f['victorias'] ?></td>
            <td class="col-num"><?= (int)$f['empates'] ?></td>
            <td class="col-num col-d"><?= (int)$f['derrotas'] ?></td>
            <td class="col-pts"><?= (int)$f['puntos'] ?></td>
            <td class="col-pct hide-sm">
                <?= $f['omw_percentage'] !== null ? number_format((float)$f['omw_percentage']*100, 1).'%' : '—' ?>
            </td>
            <td class="col-pct hide-sm">
                <?= $f['pmw_percentage'] !== null ? number_format((float)$f['pmw_percentage']*100, 1).'%' : '—' ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<p class="leyenda">
    Victoria=3pts · Empate=1pt · Derrota=0pts ·
    OMW% = Opp. Match Win · PMW% = Player Match Win
</p>
<?php endif; ?>

<style>
.page-header  { margin:2rem 0 1.25rem; }
.page-heading { font-family:'Orbitron',sans-serif; font-size:1.4rem; font-weight:900; color:var(--amarillo); }
.page-sub     { color:var(--texto); font-size:.85rem; margin-top:.3rem; }

.selector-form  { margin-bottom:1rem; }
.selector-label { display:block; font-size:.7rem; font-weight:700; letter-spacing:.12em;
                  text-transform:uppercase; color:var(--texto); margin-bottom:.4rem; }
.selector-select{
    background:var(--card); border:1px solid var(--border); border-radius:10px;
    padding:.6rem 2.25rem .6rem .9rem; color:var(--blanco); font-family:'Nunito',sans-serif;
    font-size:.9rem; appearance:none; outline:none; cursor:pointer;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath fill='%238888a8' d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right .75rem center; transition:border-color .2s;
}
.selector-select:focus{ border-color:var(--purpura); }

.torneo-info-bar{
    display:flex; flex-wrap:wrap; gap:.5rem 1.25rem;
    font-size:.78rem; color:var(--texto); margin-bottom:1rem;
    padding:.75rem 1rem; background:var(--card);
    border:1px solid var(--border); border-radius:10px;
    align-items:center;
}
.estado-badge{ font-weight:700; padding:.2rem .65rem; border-radius:12px; font-size:.7rem; }
.estado-en_curso   { background:rgba(6,214,160,.12); color:var(--verde); }
.estado-finalizado { background:rgba(176,176,200,.08); color:var(--texto); }
.estado-pendiente  { background:rgba(245,197,24,.1);  color:var(--amarillo); }

.table-wrapper{ background:var(--card); border:1px solid var(--border); border-radius:14px; overflow:hidden; }
.data-table   { width:100%; border-collapse:collapse; font-size:.875rem; }
.data-table thead{ background:rgba(255,255,255,.03); border-bottom:1px solid var(--border); }
.data-table th{ padding:.75rem 1rem; font-size:.65rem; font-weight:700;
                letter-spacing:.12em; text-transform:uppercase; color:var(--texto); text-align:center; }
.data-table td{ padding:.8rem 1rem; border-top:1px solid var(--border);
                text-align:center; color:var(--texto-claro); vertical-align:middle; }
.data-table tbody tr:hover td{ background:rgba(255,255,255,.025); }
.fila-lider td{ background:rgba(245,197,24,.04); }

.col-pos    { width:52px; }
.col-jugador{ text-align:left !important; }
.col-div    { width:60px; font-size:.75rem; color:var(--texto); }
.col-num    { width:44px; }
.col-v      { color:var(--verde) !important; font-weight:700; }
.col-d      { color:var(--rojo)  !important; }
.col-pts    { font-family:'Orbitron',sans-serif; font-size:.85rem;
              font-weight:900; color:var(--amarillo) !important; width:56px; }
.col-pct    { width:72px; font-size:.78rem; color:var(--texto); }

.j-nombre{ font-weight:700; color:var(--blanco); display:block; }
.j-pid   { font-size:.7rem; color:var(--texto); font-family:monospace; display:block; }

.medalla{ display:inline-flex; align-items:center; justify-content:center;
          width:26px; height:26px; border-radius:50%; font-size:.75rem; font-weight:900; }
.medalla.oro    { background:rgba(245,197,24,.2);  color:var(--amarillo); border:1px solid rgba(245,197,24,.4); }
.medalla.plata  { background:rgba(200,200,220,.1); color:#ccc;            border:1px solid rgba(200,200,220,.3); }
.medalla.bronce { background:rgba(205,127,50,.12); color:#cd7f32;         border:1px solid rgba(205,127,50,.3); }
.pos-num{ font-size:.8rem; color:var(--texto); }

.leyenda   { font-size:.72rem; color:var(--texto); opacity:.55; margin-top:.6rem; }
.empty-state{ text-align:center; padding:3rem; background:var(--card);
              border:1px dashed var(--border); border-radius:14px; }
.empty-icon { font-size:2.5rem; display:block; opacity:.4; margin-bottom:.75rem; }

@media(max-width:700px){ .hide-sm{ display:none !important; } }
</style>

<?php include INC_PATH . '/footer.php'; ?>
