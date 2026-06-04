<?php
require_once '../../controllers/TorneoControllerAdmin.php';

$controller = new TorneoControllerAdmin();

// Crear torneo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $datos = [
        'Nombre' => $_POST['Nombre'],
        'Tipo_Torneo' => $_POST['Tipo_Torneo'],
        'fecha_inicio' => $_POST['fecha_inicio'],
        'fecha_fin' => $_POST['fecha_fin'],
        'Ubicacion' => $_POST['Ubicacion'],
        'Pais' => $_POST['Pais'],
        'Num_Rondas_Suizas' => (int)$_POST['Num_Rondas_Suizas'],
        'Tamanio_Top_Cut' => (int)$_POST['Tamanio_Top_Cut'],
        'ID_Temporada' => (int)$_POST['ID_Temporada']
    ];
    $controller->crear($datos);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Eliminar torneo
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    if ($id > 0) {
        $controller->eliminar($id);
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
$torneos = $controller->index();
$temporadas = $controller->getTemporadas();  // ← Aquí obtenemos las temporadas

include '../../../includes/header.php';
?>

<div class="page">

    <div class="page-header">
        <h1 class="page-title">Torneos</h1>
        <p class="page-subtitle">Gestión de competiciones registradas</p>
    </div>

    <div class="card">

        <button id="btnMostrarForm" class="btn-primary">
            + Nuevo torneo
        </button>

        <div id="formCrear" style="display:none; margin-top:1rem;">

            <form method="POST" class="form-grid">

                <input type="text" name="nombre" placeholder="Nombre" required>

                <select name="Tipo_Torneo" id="select" required>
                    <option value="">Seleccionar tipo</option>
                    <option value="Regional">Regional</option>
                    <option value="League Cup">League Cup</option>
                    <option value="Internacional">Internacional</option>
                </select>

                <input type="date" name="fecha_inicio" required>
                <input type="date" name="fecha_fin" required>

                <input type="text" name="Ubicacion" placeholder="Ubicación">
                <input type="text" name="Pais" placeholder="País" required>

                <input type="number" name="Num_Rondas_Suizas" placeholder="Rondas suizas" min="0">
                <input type="number" name="Tamanio_Top_Cut" placeholder="Top Cut" min="0">

                <select name="ID_Temporada" id="select" required>
                    <option value="">-- Selecciona temporada --</option>
                    <?php foreach ($temporadas as $temp): ?>
                        <option value="<?= $temp['ID_Temporada'] ?>"><?= htmlspecialchars($temp['ID_Temporada']) ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" name="crear" class="btn-success">
                    Guardar torneo
                </button>

            </form>

        </div>

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
                        <th>Tipo</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Ubicación</th>
                        <th>País</th>
                        <th>Rondas</th>
                        <th>Top Cut</th>
                        <th>Temp</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($torneos as $t): ?>
                    <tr>

                        <td class="td-title">
                            <?= htmlspecialchars($t['nombre']) ?>
                        </td>

                        <td>
                            <span class="badge badge-amarillo">
                                <?= htmlspecialchars($t['Tipo_Torneo']) ?>
                            </span>
                        </td>

                        <td><?= date('d/m/Y', strtotime($t['fecha_inicio'])) ?></td>
                        <td><?= date('d/m/Y', strtotime($t['fecha_fin'])) ?></td>

                        <td><?= htmlspecialchars($t['Ubicacion']) ?></td>

                        <td>
                            <span class="badge badge-purple">
                                <?= htmlspecialchars($t['Pais']) ?>
                            </span>
                        </td>

                        <td><?= $t['Num_Rondas_Suizas'] ?></td>
                        <td><?= $t['Tamanio_Top_Cut'] ?></td>
                        <td><?= $t['ID_Temporada'] ?></td>

                        <td>
                            <a href="?eliminar=<?= $t['ID_Torneo'] ?>"
                               class="btn-danger"
                               onclick="return confirm('¿Eliminar torneo?')">
                                🗑️
                            </a>
                        </td>

                    </tr>
                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</div>

<style>

.page{
    padding:1.5rem;
    width:min(1400px, 100% - 3rem);
    margin:0 auto;
}

.page-title{
    font-size:1.4rem;
    font-weight:900;
    color:var(--texto);
}

.page-subtitle{
    font-size:.95rem;
    color:var(--texto);
    margin-bottom:1rem;
}

    .card{
        background:linear-gradient(145deg,#0f0f1c,#0c0c18);
        border:1px solid var(--border);
        border-radius:14px;
        padding:1rem;
        margin-bottom:1.5rem;

    }

/* FORM */
.form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(180px,1fr));
    gap:10px;
    font-size:0.95rem;
    font-family: Tahoma, sans-serif;
    color:var(--texto);
}

.form-grid input,
.form-grid select{
    padding:8px;
    border-radius:8px;
    border:1px solid var(--border);
    background:rgba(255,255,255,0.03);
    color:white;
    font-size:0.95rem;
    font-family: Tahoma, sans-serif;
    color:var(--texto); 
}

/* BUTTONS */
.btn-primary{
    background:var(--purpura);
    border:none;
    padding:8px 12px;
    border-radius:8px;
    color:white;
    font-weight:700;
    cursor:pointer;
}

.btn-success{
    background:var(--verde);
    border:none;
    padding:8px 12px;
    border-radius:8px;
    font-weight:700;
    cursor:pointer;
}

.btn-danger{
    background:rgba(255,77,109,0.2);
    padding:6px 8px;
    border-radius:8px;
    text-decoration:none;
    display:inline-block;
}

.table-wrapper{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:14px;
    overflow:hidden;
}

.data-table{
    width:100%;
    border-collapse:collapse;
}

.data-table th{
    text-align:left;
    padding:.9rem 1rem;
    font-size:.65rem;
    text-transform:uppercase;
    color:var(--texto);
}

.data-table td{
    padding:.9rem 1rem;
    border-top:1px solid var(--border);
    color:var(--texto-claro);
}

.data-table tbody tr:hover{
    background:rgba(124,92,252,0.06);
}

.empty-state{
    text-align:center;
    padding:3rem 1rem;
    color:var(--texto);
}

.empty-icon{
    font-size:2.5rem;
    opacity:.4;
}

#select {
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
#select:hover {
    border-color: var(--verde);
}

/* Focus */
#select:focus {
    border-color: var(--verde);
    box-shadow: 0 0 0 2px rgba(0, 255, 120, 0.15);
}

</style>

<script>
document.getElementById('btnMostrarForm').addEventListener('click', function () {
    const form = document.getElementById('formCrear');

    if (form.style.display === 'none') {
        form.style.display = 'block';
        this.textContent = '✖ Cerrar formulario';
    } else {
        form.style.display = 'none';
        this.textContent = '+ Nuevo torneo';
    }
});

document.querySelector('select[name="Tipo_Torneo"]').addEventListener('change', function() {
    const tipo = this.value;
    const rondas = { 'Regional':8, 'League Cup':5, 'Internacional':9 };
    const top = { 'Regional':32, 'League Cup':8, 'Internacional':32 };
    if (rondas[tipo]) {
        document.querySelector('input[name="Num_Rondas_Suizas"]').value = rondas[tipo];
        document.querySelector('input[name="Tamanio_Top_Cut"]').value = top[tipo];
    }
});
</script>



<?php include '../../../includes/footer.php'; ?>