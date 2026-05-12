<?php include INC_PATH . '/header.php'; ?>

<div class="page-header">
    <h1 class="page-heading">Clasificación</h1>
    <p class="page-sub">Tabla actualizada en tiempo real</p>
</div>

<?php if (empty($tabla)): ?>
    <div class="empty-state">
        <span class="empty-icon">🏆</span>
        <p>Aún no hay partidos jugados para calcular la clasificación.</p>
    </div>
<?php else: ?>
<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Equipo</th>
                <th title="Partidos jugados">PJ</th>
                <th title="Ganados">G</th>
                <th title="Empatados">E</th>
                <th title="Perdidos">P</th>
                <th title="Goles a favor">GF</th>
                <th title="Goles en contra">GC</th>
                <th title="Diferencia de goles">DG</th>
                <th title="Puntos">Pts</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tabla as $pos => $equipo): ?>
            <tr class="<?= $pos === 0 ? 'row-lider' : '' ?>">
                <td class="td-pos">
                    <?php if ($pos === 0): ?>
                        <span class="pos-badge gold">1</span>
                    <?php elseif ($pos === 1): ?>
                        <span class="pos-badge silver">2</span>
                    <?php elseif ($pos === 2): ?>
                        <span class="pos-badge bronze">3</span>
                    <?php else: ?>
                        <span class="pos-num"><?= $pos + 1 ?></span>
                    <?php endif; ?>
                </td>
                <!-- <td class="td-equipo-nombre">
                    <?= htmlspecialchars($equipo['equipo']) ?>
                </td>
                <td><?= $equipo['pj'] ?></td>
                <td class="td-g"><?= $equipo['pg'] ?></td>
                <td><?= $equipo['pe'] ?></td>
                <td class="td-p"><?= $equipo['pp'] ?></td>
                <td><?= $equipo['gf'] ?></td>
                <td><?= $equipo['gc'] ?></td>
                <td class="<?= $equipo['dg'] > 0 ? 'td-pos-val' : ($equipo['dg'] < 0 ? 'td-neg-val' : '') ?>">
                    <?= $equipo['dg'] > 0 ? '+' : '' ?><?= $equipo['dg'] ?>
                </td>
                <td class="td-pts"><?= $equipo['pts'] ?></td> -->
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="table-leyenda">
    <span>PJ = Partidos Jugados</span>
    <span>G = Ganados · E = Empatados · P = Perdidos</span>
    <span>GF = Goles a Favor · GC = Goles en Contra · DG = Diferencia</span>
    <span>Pts = Puntos</span>
</div>
<?php endif; ?>

<style>
.page-header    { margin: 2rem 0 1.5rem; }
.page-heading   { font-family: 'Orbitron', sans-serif; font-size: 1.4rem; font-weight: 900; color: var(--amarillo); }
.page-sub       { color: var(--texto); font-size: .85rem; margin-top: .3rem; }

.table-wrapper  { background: var(--card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
.data-table     { width: 100%; border-collapse: collapse; font-size: .9rem; }
.data-table thead { background: rgba(255,255,255,.03); }
.data-table th  { padding: .75rem 1rem; font-size: .65rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: var(--texto); text-align: center; }
.data-table th:nth-child(2) { text-align: left; }
.data-table td  { padding: .75rem 1rem; border-top: 1px solid var(--border); text-align: center; color: var(--texto-claro); }
.data-table tbody tr:hover td { background: rgba(255,255,255,.025); }

.row-lider td   { background: rgba(245,197,24,.04); }
.td-equipo-nombre { text-align: left; font-weight: 700; color: var(--blanco); }
.td-pts         { font-weight: 900; color: var(--amarillo); font-size: 1rem; }
.td-g           { color: var(--verde); font-weight: 700; }
.td-p           { color: var(--rojo); }
.td-pos         { width: 48px; }
.td-pos-val     { color: var(--verde); font-weight: 700; }
.td-neg-val     { color: var(--rojo); }

.pos-badge      { display: inline-flex; align-items: center; justify-content: center;
                  width: 26px; height: 26px; border-radius: 50%; font-size: .75rem; font-weight: 900; }
.pos-badge.gold   { background: rgba(245,197,24,.2); color: var(--amarillo); border: 1px solid rgba(245,197,24,.4); }
.pos-badge.silver { background: rgba(200,200,220,.12); color: #ccc; border: 1px solid rgba(200,200,220,.3); }
.pos-badge.bronze { background: rgba(205,127,50,.15); color: #cd7f32; border: 1px solid rgba(205,127,50,.3); }
.pos-num        { font-size: .8rem; color: var(--texto); }

.table-leyenda  { display: flex; flex-wrap: wrap; gap: .5rem 1.5rem; margin-top: .75rem;
                  font-size: .7rem; color: var(--texto); opacity: .6; }

.empty-state    { text-align: center; padding: 3rem; background: var(--card);
                  border: 1px dashed var(--border); border-radius: 14px; }
.empty-icon     { font-size: 2.5rem; display: block; opacity: .4; margin-bottom: .75rem; }

@media (max-width: 600px) {
    .data-table th:nth-child(n+8),
    .data-table td:nth-child(n+8) { display: none; }
}
</style>

<?php include INC_PATH . '/footer.php'; ?>
