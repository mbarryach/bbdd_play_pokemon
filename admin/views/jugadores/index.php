<?php
require_once '../../controllers/JugadorControllerAdmin.php';

// Procesar acciones enviadas por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $accion = $_POST['accion'] ?? '';

    if ($id > 0) {
        $controller = new JugadorControllerAdmin();
        switch ($accion) {
            case 'mas10':
                $controller->sumarCP($id);
                break;
            case 'menos10':
                $controller->restarCP($id);
                break;
            case 'eliminar':
                $controller->eliminarJugador($id);
                break;
            case 'sancion':
                $controller->aplicarSancion($id);
                break;
        }
    }
    // Redirigir para evitar reenvío del formulario
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Cargar lista de jugadores
$controller = new JugadorControllerAdmin();
$jugadores = $controller->index();

include '../../../includes/header.php';
?>

<div class="page">

    <div class="page-header">
        <h1 class="page-title">Jugadores - Panel Admin</h1>
        <p class="page-subtitle">Gestiona CP, torneos y sanciones</p>
    </div>

    <?php if (empty($jugadores)): ?>
        <div class="empty-state">
            <div class="empty-icon">🎴</div>
            <p>No hay jugadores registrados</p>
        </div>
    <?php else: ?>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Jugador</th>
                    <th>País</th>
                    <th>División</th>
                    <th>CP Totales</th>
                    <th>CP Temporada</th>
                    <th>Torneos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($jugadores as $j): ?>
                <tr data-id="<?= $j['ID_Jugador'] ?>">
                    <td>
                        <span class="player-name">
                            <?= htmlspecialchars($j['nombre_completo']) ?>
                        </span>
                    </td>
                    <td><span class="badge badge-purpura"><?= htmlspecialchars($j['pais']) ?></span></td>
                    <td><span class="badge badge-amarillo"><?= htmlspecialchars($j['division']) ?></span></td>
                    <td class="cp-totales"><?= number_format($j['cp_totales'], 0, ',', '.') ?></td>
                    <td class="cp-temporada"><?= number_format($j['cp_temporada_actual'], 0, ',', '.') ?></td>
                    <td class="torneos"><?= $j['torneos_jugados'] ?></td>
                    <td class="acciones">
                        <div class="btn-group">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="id" value="<?= $j['ID_Jugador'] ?>">
                                <input type="hidden" name="accion" value="mas10">
                                <button type="submit" class="btn mas10">+10</button>
                            </form>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="id" value="<?= $j['ID_Jugador'] ?>">
                                <input type="hidden" name="accion" value="menos10">
                                <button type="submit" class="btn menos10">-10</button>
                            </form>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="id" value="<?= $j['ID_Jugador'] ?>">
                                <input type="hidden" name="accion" value="sancion">
                                <button type="submit" class="btn sancion">⚠️</button>
                            </form>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('¿Eliminar jugador permanentemente? Se borrarán también sus emparejamientos.');">
                                <input type="hidden" name="id" value="<?= $j['ID_Jugador'] ?>">
                                <input type="hidden" name="accion" value="eliminar">
                                <button type="submit" class="btn eliminar">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php endif; ?>
</div>

<style>
/* PAGE */
.page {
    padding: 1.5rem;
    max-width: 1200px;
    margin: 0 auto;
}

.page-title {
    font-family: 'Orbitron', sans-serif;
    font-size: 1.4rem;
    font-weight: 900;
    color: var(--blanco);
}

.page-subtitle {
    font-size: .85rem;
    color: var(--texto);
}

/* TABLE */
.table-wrapper {
    background: linear-gradient(145deg, #0f0f1c, #0c0c18);
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    padding: .9rem 1rem;
    text-align: left;
    font-size: .65rem;
    text-transform: uppercase;
    color: var(--texto);
}

.data-table td {
    padding: .85rem 1rem;
    border-top: 1px solid var(--border);
    color: var(--texto-claro);
}

.data-table tbody tr:hover {
    background: rgba(124,92,252,0.08);
}

/* BADGES */
.badge {
    padding: .25rem .6rem;
    border-radius: 999px;
    font-size: .7rem;
    font-weight: 800;
}

.badge-purpura { background: rgba(124,92,252,0.12); color: var(--purpura); }
.badge-amarillo { background: rgba(245,197,24,0.12); color: var(--amarillo); }

/* BUTTONS */
.btn-group {
    display: flex;
    gap: 6px;
}

.btn {
    border: 1px solid var(--border);
    background: rgba(255,255,255,0.04);
    color: var(--texto-claro);
    padding: 5px 10px;
    border-radius: 8px;
    font-size: .7rem;
    font-weight: 700;
    cursor: pointer;
    transition: .2s;
}

.btn:hover {
    transform: translateY(-1px);
    background: rgba(124,92,252,0.15);
    color: var(--blanco);
}

.btn.mas10:hover { background: rgba(40,167,69,0.15); }
.btn.menos10:hover { background: rgba(220,53,69,0.15); }
.btn.eliminar:hover { background: rgba(220,53,69,0.15); }
.btn.sancion:hover { background: rgba(255,193,7,0.15); }

/* EMPTY */
.empty-state {
    text-align: center;
    padding: 3rem;
    color: var(--texto);
}

.empty-icon {
    font-size: 2.5rem;
    opacity: .4;
}
</style>

<?php include '../../../includes/footer.php'; ?>