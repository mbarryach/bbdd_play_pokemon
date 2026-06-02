<?php 
include '../../includes/header.php'; 
require_once '../../controllers/ClasificacionController.php';

$controller = new ClasificacionController();
$torneos = $controller->getTorneos();

$torneoId = isset($_GET['torneo']) ? (int)$_GET['torneo'] : 0;
$tabla = [];

if ($torneoId > 0) {
    $tabla = $controller->getClasificacionByTorneo($torneoId);
}
?>

<div class="page">

    <div class="page-header">
        <h1 class="page-title">Clasificación</h1>
        <p class="page-subtitle">Consulta la tabla de posiciones del torneo</p>
    </div>

    <div class="card">

        <form method="GET" class="form-inline">
            <label class="form-label">Torneo</label>

            <select name="torneo" class="form-control select-modern" onchange="this.form.submit()">
                <option value="">-- Selecciona torneo --</option>

                <?php foreach ($torneos as $t): ?>
                    <option value="<?= $t['ID_Torneo'] ?>"
                        <?= (isset($_GET['torneo']) && $_GET['torneo'] == $t['ID_Torneo']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['Nombre']) ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </form>

    </div>

    <?php if (empty($torneos)): ?>
        <div class="empty-state">
            <div class="empty-icon">🏆</div>
            <p>No hay torneos disponibles</p>
        </div>

    <?php else: ?>

        <?php if ($torneoId == 0): ?>
            <div class="empty-state">
                <div class="empty-icon">📊</div>
                <p>Selecciona un torneo para ver la clasificación</p>
            </div>

        <?php elseif (empty($tabla)): ?>
            <div class="empty-state">
                <div class="empty-icon">⚔️</div>
                <p>No hay clasificación para este torneo</p>
            </div>

        <?php else: ?>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Jugador</th>
                            <th>Puntos</th>
                            <th>Victorias</th>
                            <th>Derrotas</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($tabla as $row): ?>
                            <tr>
                                <td class="td-equipo">
                                    <?= htmlspecialchars($row['jugador']) ?>
                                </td>

                                <td>
                                    <span class="badge badge-amarillo">
                                        <?= $row['puntos'] ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="badge badge-verde">
                                        <?= $row['victorias'] ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="badge badge-rojo">
                                        <?= $row['derrotas'] ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>

        <?php endif; ?>
    <?php endif; ?>

</div>

<style>

/* ─────────────────────────────────────────────
   PAGE LAYOUT
───────────────────────────────────────────── */

.page {
    padding: 1.5rem;
    width: min(1400px, 100% - 3rem);
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
    margin-bottom: .25rem;
}

.page-subtitle {
    font-size: .85rem;
    color: var(--texto);
}

/* ─────────────────────────────────────────────
   CARD FORM
───────────────────────────────────────────── */

.card {
    background: linear-gradient(145deg, #0f0f1c, #0c0c18);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

/* ─────────────────────────────────────────────
   FORM
───────────────────────────────────────────── */

.form-inline {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.form-label {
    font-size: .75rem;
    font-weight: 700;
    color: var(--texto);
    text-transform: uppercase;
    letter-spacing: .1em;
}

.select-modern {
    min-width: 240px;
    background: rgba(255,255,255,.04);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: .6rem .9rem;
    color: var(--blanco);
    font-weight: 600;
    outline: none;
    transition: border .2s, box-shadow .2s;
}

.select-modern:focus {
    border-color: var(--purpura);
    box-shadow: 0 0 0 3px rgba(124,92,252,.15);
}

/* ─────────────────────────────────────────────
   TABLE IMPROVED
───────────────────────────────────────────── */

.table-wrapper {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(0,0,0,.35);
}

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
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--texto);
}

.data-table td {
    padding: .9rem 1rem;
    border-top: 1px solid var(--border);
    color: var(--texto-claro);
}

.data-table tbody tr:hover {
    background: rgba(124,92,252,.06);
}

/* ─────────────────────────────────────────────
   BADGES (refuerzo visual ranking)
───────────────────────────────────────────── */

.badge {
    font-size: .7rem;
    padding: .25rem .6rem;
    border-radius: 999px;
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

/* ─────────────────────────────────────────────
   EMPTY STATE (unificado con resto sistema)
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

@media (max-width: 640px) {
    .form-inline {
        flex-direction: column;
        align-items: stretch;
    }

    .select-modern {
        width: 100%;
    }
}

</style>

<?php include '../../includes/footer.php'; ?>