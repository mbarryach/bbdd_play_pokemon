<?php 
include '../../includes/header.php'; 
require_once '../../controllers/JugadorController.php';

$controller = new JugadorController();
$jugadores = $controller->index();
$cantidad = isset($_GET['cantidad']) ? (int)$_GET['cantidad'] : 10;
$jugadores = $controller->ultimos($cantidad);
?>

<div class="page">

    <div class="page-header">
        <h1 class="page-title">Jugadores</h1>
        <p class="page-subtitle">Listado de jugadores registrados en el sistema</p>
    </div>

    <form method="GET" action="" style="margin-bottom: 1rem;">
        <label for="cantidad"></label>
        <select name="cantidad" id="cantidad" onchange="this.form.submit()">
            <option value="10" <?= $cantidad == 10 ? 'selected' : '' ?>>10</option>
            <option value="20" <?= $cantidad == 20 ? 'selected' : '' ?>>20</option>
            <option value="50" <?= $cantidad == 50 ? 'selected' : '' ?>>50</option>
            <option value="999" <?= $cantidad == 999 ? 'selected' : '' ?>>max.</option>
        </select>
    </form>

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
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($jugadores as $j): ?>
                    <tr>

                        <td>
                            <span class="player-name">
                                <?= htmlspecialchars($j['nombre_completo']) ?>
                            </span>
                        </td>

                        <td>
                            <span class="badge badge-purpura">
                                <?= htmlspecialchars($j['pais']) ?>
                            </span>
                        </td>

                        <td>
                            <span class="badge badge-amarillo">
                                <?= htmlspecialchars($j['division']) ?>
                            </span>
                        </td>

                        <td>
                            <span class="badge badge-verde">
                                <?= number_format($j['cp_totales'], 0, ',', '.') ?>
                            </span>
                        </td>

                        <td>
                            <span class="badge badge-verde">
                                <?= number_format($j['cp_temporada_actual'], 0, ',', '.') ?>
                            </span>
                        </td>

                        <td>
                            <span class="badge badge-rojo">
                                <?= $j['torneos_jugados'] ?>
                            </span>
                        </td>

                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>

        </div>

    <?php endif; ?>

</div>

<style>

/* PAGE BASE */
.page {
    padding: 1.5rem;
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 1.5rem;
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
    margin-top: .25rem;
}

/* TABLE WRAPPER */
.table-wrapper {
    background: linear-gradient(145deg, #0f0f1c, #0c0c18);
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 10px 35px rgba(0,0,0,.35);
}

/* TABLE */
.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead {
    background: rgba(255,255,255,.03);
}

.data-table th {
    padding: .9rem 1rem;
    text-align: left;
    font-size: .65rem;
    text-transform: uppercase;
    letter-spacing: .12em;
    color: var(--texto);
}

.data-table td {
    padding: .85rem 1rem;
    border-top: 1px solid var(--border);
    color: var(--texto-claro);
    font-size: .85rem;
}

.data-table tbody tr:hover {
    background: rgba(124,92,252,.06);
}

/* PLAYER NAME */
.player-name {
    font-weight: 700;
    color: var(--blanco);
}

/* BADGES */
.badge {
    display: inline-flex;
    padding: .25rem .6rem;
    border-radius: 999px;
    font-size: .7rem;
    font-weight: 800;
}

.badge-amarillo {
    background: rgba(245,197,24,.12);
    color: var(--amarillo);
}

.badge-verde {
    background: rgba(6,214,160,.12);
    color: var(--verde);
}

.badge-rojo {
    background: rgba(255,77,109,.12);
    color: var(--rojo);
}

.badge-purpura {
    background: rgba(124,92,252,.12);
    color: var(--purpura);
}

/* EMPTY */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: var(--texto);
}

.empty-icon {
    font-size: 2.5rem;
    opacity: .4;
    margin-bottom: .5rem;
}

#cantidad {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border);
    color: var(--texto);
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 0.95rem;
    cursor: pointer;
    outline: none;
    transition: all 0.2s ease;
}

/* Hover */
#cantidad:hover {
    border-color: var(--verde);
}

/* Focus */
#cantidad:focus {
    border-color: var(--verde);
    box-shadow: 0 0 0 2px rgba(0, 255, 120, 0.15);
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .table-wrapper {
        overflow-x: auto;
    }

    .data-table th,
    .data-table td {
        white-space: nowrap;
    }
}

</style>

<?php include '../../includes/footer.php'; ?>