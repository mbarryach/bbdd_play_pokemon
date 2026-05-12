<?php include INC_PATH . '/header.php'; ?>

<div class="page-header">
    <div>
        <h1 class="page-heading">Jugadores</h1>
        <p class="page-sub"><?= count($jugadores) ?> jugadores<?= $busqueda !== '' ? ' encontrados para "' . htmlspecialchars($busqueda) . '"' : '' ?></p>
    </div>
</div>

<!-- Buscador -->
<form method="GET" action="" class="buscador-form">
    <div class="buscador-wrap">
        <input
            type="text"
            name="q"
            class="buscador-input"
            placeholder="Buscar por nombre o apodo..."
            value="<?= htmlspecialchars($busqueda) ?>"
            autocomplete="off"
        >
        <button type="submit" class="buscador-btn">Buscar</button>
        <?php if ($busqueda !== ''): ?>
            <a href="jugadores.php" class="buscador-clear">✕ Limpiar</a>
        <?php endif; ?>
    </div>
</form>

<?php if (empty($jugadores)): ?>
    <div class="empty-state">
        <span class="empty-icon">🎴</span>
        <p>No se encontraron jugadores<?= $busqueda !== '' ? ' con ese nombre.' : '.' ?></p>
    </div>
<?php else: ?>
<div class="jugadores-grid">
    <?php foreach ($jugadores as $j): ?>
    <div class="jugador-card fade-in">
        <div class="jugador-avatar">
            <?= mb_strtoupper(mb_substr($j['nombre'], 0, 1)) ?>
        </div>
        <div class="jugador-info">
            <div class="jugador-nombre"><?= htmlspecialchars($j['nombre_completo']) ?></div>
            <?php if ($j['apodo']): ?>
                <div class="jugador-apodo">"<?= htmlspecialchars($j['apodo']) ?>"</div>
            <?php endif; ?>
            <div class="jugador-equipo">
                🛡️ <?= $j['equipo'] ? htmlspecialchars($j['equipo']) : '<span style="opacity:.5">Sin equipo</span>' ?>
            </div>
        </div>
        <?php if (!$j['activo']): ?>
            <span class="badge-inactivo">Inactivo</span>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<style>
.page-header  { margin: 2rem 0 1.25rem; }
.page-heading { font-family: 'Orbitron', sans-serif; font-size: 1.4rem; font-weight: 900; color: var(--amarillo); }
.page-sub     { color: var(--texto); font-size: .85rem; margin-top: .3rem; }

.buscador-form  { margin-bottom: 1.5rem; }
.buscador-wrap  { display: flex; gap: .5rem; flex-wrap: wrap; }
.buscador-input {
    flex: 1; min-width: 200px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: .65rem 1rem;
    color: var(--blanco);
    font-family: 'Nunito', sans-serif;
    font-size: .9rem;
    outline: none;
    transition: border-color .2s;
}
.buscador-input:focus { border-color: var(--purpura); }
.buscador-btn {
    padding: .65rem 1.25rem;
    background: var(--purpura);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-family: 'Nunito', sans-serif;
    font-size: .9rem;
    font-weight: 700;
    cursor: pointer;
    transition: background .15s;
}
.buscador-btn:hover { background: #6a4de0; }
.buscador-clear {
    display: flex; align-items: center;
    padding: .65rem 1rem;
    background: rgba(255,77,109,.1);
    color: var(--rojo);
    border: 1px solid rgba(255,77,109,.3);
    border-radius: 10px;
    font-size: .85rem;
    font-weight: 700;
    transition: background .15s;
}
.buscador-clear:hover { background: rgba(255,77,109,.2); }

.jugadores-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
    gap: 1rem;
}

.jugador-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 1.1rem;
    display: flex;
    align-items: center;
    gap: .85rem;
    transition: border-color .2s, transform .2s;
    position: relative;
}
.jugador-card:hover { border-color: rgba(124,92,252,.4); transform: translateY(-2px); }

.jugador-avatar {
    width: 44px; height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--purpura), #4a2fcc);
    display: flex; align-items: center; justify-content: center;
    font-weight: 900; font-size: 1.1rem; color: #fff;
    flex-shrink: 0;
}

.jugador-nombre { font-weight: 700; font-size: .9rem; color: var(--blanco); }
.jugador-apodo  { font-size: .78rem; color: var(--amarillo); font-style: italic; margin-top: .15rem; }
.jugador-equipo { font-size: .75rem; color: var(--texto); margin-top: .3rem; }

.badge-inactivo {
    position: absolute; top: .6rem; right: .6rem;
    font-size: .6rem; font-weight: 700; letter-spacing: .08em;
    padding: .2rem .5rem; border-radius: 10px;
    background: rgba(255,77,109,.12); color: var(--rojo);
    border: 1px solid rgba(255,77,109,.3);
}

.empty-state { text-align: center; padding: 3rem; background: var(--card);
               border: 1px dashed var(--border); border-radius: 14px; }
.empty-icon  { font-size: 2.5rem; display: block; opacity: .4; margin-bottom: .75rem; }

.fade-in { animation: fadeIn .3s ease both; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }
</style>

<?php include INC_PATH . '/footer.php'; ?>
