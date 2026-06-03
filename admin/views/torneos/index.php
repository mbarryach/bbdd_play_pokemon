<?php
include '../../includes/header.php'; 
require_once '../../controllers/TorneoController.php';

$controller = new TorneoController();
$torneos = $controller->index();
?>

<div class="page">

    <div class="page-header">
        <h1 class="page-title">Torneos</h1>
        <p class="page-subtitle">Gestión de competiciones registradas</p>
    </div>

    <?php if (empty($torneos)): ?>

        <div class="empty-state">
            <div class="empty-icon">🏆</div>
            <p>No hay torneos registrados</p>
        </div>

    <?php else: ?>

        <div class="table-wrapper">

            <table class="data-table">

                <thead>
                    <tr>
                        <th>Torneo</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Ubicación</th>
                        <th>País</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($torneos as $t): ?>
                    <tr>

                        <td class="td-title">
                            <?= htmlspecialchars($t['nombre']) ?>
                        </td>

                        <td>
                            <span class="badge badge-green">
                                <?= date('d/m/Y', strtotime($t['fecha_inicio'])) ?>
                            </span>
                        </td>

                        <td>
                            <span class="badge badge-red">
                                <?= date('d/m/Y', strtotime($t['fecha_fin'])) ?>
                            </span>
                        </td>

                        <td class="td-muted">
                            <?= htmlspecialchars($t['Ubicacion']) ?>
                        </td>

                        <td>
                            <span class="badge badge-purple">
                                <?= htmlspecialchars($t['Pais']) ?>
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

/* ─────────────────────────────────────────────
   PAGE
───────────────────────────────────────────── */

.page {
    padding: 1.5rem;
    max-width: 1100px;
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

/* ─────────────────────────────────────────────
   TABLE WRAPPER (premium style unificado)
───────────────────────────────────────────── */

.table-wrapper {
    background: linear-gradient(145deg, #0f0f1c, #0c0c18);
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 10px 35px rgba(0,0,0,.35);
}

/* ─────────────────────────────────────────────
   TABLE
───────────────────────────────────────────── */

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

/* ─────────────────────────────────────────────
   TEXT STYLES
───────────────────────────────────────────── */

.td-title {
    font-weight: 800;
    color: var(--blanco);
}

.td-muted {
    color: var(--texto);
}

/* ─────────────────────────────────────────────
   BADGES (consistente sistema global)
───────────────────────────────────────────── */

.badge {
    display: inline-flex;
    padding: .25rem .6rem;
    border-radius: 999px;
    font-size: .7rem;
    font-weight: 800;
}

/* fechas inicio */
.badge-green {
    background: rgba(6,214,160,.12);
    color: var(--verde);
}

/* fechas fin */
.badge-red {
    background: rgba(255,77,109,.12);
    color: var(--rojo);
}

/* país */
.badge-purple {
    background: rgba(124,92,252,.12);
    color: var(--purpura);
}

/* ─────────────────────────────────────────────
   EMPTY STATE
───────────────────────────────────────────── */

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

/* ─────────────────────────────────────────────
   RESPONSIVE
───────────────────────────────────────────── */

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