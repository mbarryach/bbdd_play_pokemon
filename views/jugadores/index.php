<?php include INC_PATH . '/header.php'; ?>

<div class="page-header">
    <div>
        <h1 class="page-heading">Jugadores</h1>
        <p class="page-sub">
            <?= count($jugadores) ?> jugador<?= count($jugadores) !== 1 ? 'es' : '' ?>
            <?= $busqueda !== '' ? '— búsqueda: "' . htmlspecialchars($busqueda) . '"' : 'registrados' ?>
        </p>
    </div>
</div>

<!-- Buscador: nombre, apellido o Player ID -->
<form method="GET" class="buscador-form">
    <div class="buscador-wrap">
        <input type="text" name="q" class="buscador-input"
               placeholder="Buscar por nombre, apellido o Player ID..."
               value="<?= htmlspecialchars($busqueda) ?>" autocomplete="off">
        <button type="submit" class="buscador-btn">Buscar</button>
        <?php if ($busqueda !== ''): ?>
            <a href="jugadores.php" class="buscador-clear">✕ Limpiar</a>
        <?php endif; ?>
    </div>
</form>

<?php if (empty($jugadores)): ?>
<div class="empty-state">
    <span class="empty-icon">🎴</span>
    <p>No se encontraron jugadores<?= $busqueda !== '' ? ' con esa búsqueda.' : '.' ?></p>
</div>
<?php else: ?>

<!-- Vista tabla para poder ver Player ID, División y CP -->
<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th style="text-align:left">Jugador</th>
                <th>Player ID</th>
                <th>División</th>
                <th>País</th>
                <th title="Championship Points temporada actual">CP</th>
                <th title="Torneos disputados">Torneos</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($jugadores as $j): ?>
        <tr>
            <td class="col-jugador-info">
                <div class="jugador-avatar-sm">
                    <?= mb_strtoupper(mb_substr($j['Nombre'], 0, 1)) ?>
                </div>
                <div>
                    <span class="j-nombre"><?= htmlspecialchars($j['nombre_completo']) ?></span>
                </div>
            </td>
            <td>
                <?php if ($j['Player_ID']): ?>
                    <span class="mono-badge"><?= htmlspecialchars($j['Player_ID']) ?></span>
                <?php else: ?>
                    <span style="opacity:.4">—</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if ($j['Division']): ?>
                    <span class="div-badge div-<?= strtolower($j['Division']) ?>">
                        <?= htmlspecialchars($j['Division']) ?>
                    </span>
                <?php else: ?>
                    <span style="opacity:.4">—</span>
                <?php endif; ?>
            </td>
            <td style="font-size:.82rem;color:var(--texto)">
                <?= htmlspecialchars($j['Pais'] ?? '—') ?>
            </td>
            <td class="col-cp">
                <?= $j['CP_Temporada_Actual'] > 0
                    ? (int)$j['CP_Temporada_Actual']
                    : '<span style="opacity:.4">0</span>' ?>
            </td>
            <td class="col-num">
                <span class="torneos-num"><?= (int)$j['torneos_jugados'] ?></span>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php endif; ?>

<style>
.page-header  { margin:2rem 0 1.25rem; }
.page-heading { font-family:'Orbitron',sans-serif; font-size:1.4rem; font-weight:900; color:var(--amarillo); }
.page-sub     { color:var(--texto); font-size:.85rem; margin-top:.3rem; }

.buscador-form{ margin-bottom:1.5rem; }
.buscador-wrap{ display:flex; gap:.5rem; flex-wrap:wrap; }
.buscador-input{
    flex:1; min-width:240px; background:var(--card); border:1px solid var(--border);
    border-radius:10px; padding:.65rem 1rem; color:var(--blanco);
    font-family:'Nunito',sans-serif; font-size:.9rem; outline:none; transition:border-color .2s;
}
.buscador-input:focus{ border-color:var(--purpura); }
.buscador-btn{
    padding:.65rem 1.25rem; background:var(--purpura); color:#fff; border:none;
    border-radius:10px; font-family:'Nunito',sans-serif; font-size:.9rem;
    font-weight:700; cursor:pointer; transition:background .15s;
}
.buscador-btn:hover{ background:#6a4de0; }
.buscador-clear{
    display:flex; align-items:center; padding:.65rem 1rem; font-size:.85rem; font-weight:700;
    background:rgba(255,77,109,.08); color:var(--rojo);
    border:1px solid rgba(255,77,109,.3); border-radius:10px;
}

.table-wrapper{ background:var(--card); border:1px solid var(--border); border-radius:14px; overflow:hidden; }
.data-table   { width:100%; border-collapse:collapse; font-size:.875rem; }
.data-table thead{ background:rgba(255,255,255,.03); border-bottom:1px solid var(--border); }
.data-table th{ padding:.75rem 1rem; font-size:.65rem; font-weight:700;
                letter-spacing:.12em; text-transform:uppercase; color:var(--texto); text-align:center; }
.data-table td{ padding:.75rem 1rem; border-top:1px solid var(--border);
                text-align:center; color:var(--texto-claro); vertical-align:middle; }
.data-table tbody tr:hover td{ background:rgba(255,255,255,.025); }

.col-jugador-info{ display:flex; align-items:center; gap:.75rem; text-align:left !important; }
.jugador-avatar-sm{
    width:34px; height:34px; border-radius:50%; flex-shrink:0;
    background:linear-gradient(135deg,var(--purpura),#4a2fcc);
    display:flex; align-items:center; justify-content:center;
    font-weight:900; font-size:.9rem; color:#fff;
}
.j-nombre{ font-weight:700; color:var(--blanco); }

.mono-badge{
    font-family:monospace; font-size:.78rem;
    background:rgba(255,255,255,.06); padding:.18rem .5rem;
    border-radius:6px; color:var(--texto-claro);
}

.div-badge{
    display:inline-block; padding:.2rem .6rem; border-radius:10px;
    font-size:.7rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase;
}
.div-masters { background:rgba(245,197,24,.12); color:var(--amarillo); }
.div-seniors { background:rgba(124,92,252,.12); color:#a78bfa; }
.div-juniors { background:rgba(6,214,160,.1);   color:var(--verde); }

.col-cp{ font-weight:700; color:var(--amarillo); }
.col-num{ width:60px; }
.torneos-num{
    display:inline-flex; align-items:center; justify-content:center;
    width:28px; height:28px; border-radius:50%; background:rgba(124,92,252,.12);
    color:#a78bfa; font-size:.78rem; font-weight:700;
}

.empty-state{ text-align:center; padding:3rem; background:var(--card);
              border:1px dashed var(--border); border-radius:14px; }
.empty-icon { font-size:2.5rem; display:block; opacity:.4; margin-bottom:.75rem; }

@media(max-width:640px){
    .data-table th:nth-child(4),.data-table td:nth-child(4){ display:none; }
}
</style>

<?php include INC_PATH . '/footer.php'; ?>
