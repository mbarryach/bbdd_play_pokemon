<?php
require_once '../../controllers/UsuariosControllerAdmin.php';

$controller = new UsuarioController();
$usuarios = $controller->index();

include '../../../includes/header.php';
?>

<div class="page">

    <div class="page-header">
        <h1 class="page-title">Usuarios</h1>
        <p class="page-subtitle">Gestión de usuarios del sistema</p>
    </div>

    <?php if (empty($usuarios)): ?>

        <div class="empty-state">
            <div class="empty-icon">👤</div>
            <p>No hay usuarios registrados</p>
        </div>

    <?php else: ?>

        <div class="table-wrapper">

            <table class="data-table">

                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Password</th>
                        <th>Rol</th>
                        <th>Registro</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>

                        <td class="td-user">
                            <?= htmlspecialchars($u['usuario']) ?>
                        </td>

                        <td>
                            <span class="password-badge">
                                ●●●●●●●●
                            </span>
                        </td>

                        <td>
                            <span class="badge badge-role role-<?= htmlspecialchars($u['rol']) ?>">
                                <?= htmlspecialchars($u['rol']) ?>
                            </span>
                        </td>

                        <td class="td-date">
                            <?= date('d/m/Y', strtotime($u['created_at'])) ?>
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
   TABLE WRAPPER
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
   PASSWORD (IMPORTANTE UX)
───────────────────────────────────────────── */

.password-badge {
    display: inline-block;
    padding: .25rem .6rem;
    border-radius: 999px;
    background: rgba(255,255,255,.04);
    border: 1px solid var(--border);
    color: var(--texto);
    font-family: monospace;
    letter-spacing: .2em;
    font-size: .75rem;
    opacity: .8;
}

/* ─────────────────────────────────────────────
   ROLE BADGES
───────────────────────────────────────────── */

.badge {
    display: inline-flex;
    padding: .25rem .6rem;
    border-radius: 999px;
    font-size: .7rem;
    font-weight: 800;
}

.role-admin {
    background: rgba(245,197,24,.12);
    color: var(--amarillo);
}

.role-arbitro {
    background: rgba(124,92,252,.12);
    color: var(--purpura);
}

.role-consulta {
    background: rgba(6,214,160,.12);
    color: var(--verde);
}

/* ─────────────────────────────────────────────
   ROW DETAILS
───────────────────────────────────────────── */

.td-user {
    font-weight: 700;
    color: var(--blanco);
}

.td-date {
    color: var(--texto);
    font-size: .8rem;
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

<?php include '../../../includes/footer.php'; ?>