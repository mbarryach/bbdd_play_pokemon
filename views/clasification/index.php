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

<!-- Resto de tu vista aquí... -->
<h1>Clasificación</h1>

<form method="GET">
    <select name="torneo" onchange="this.form.submit()">
        
        <option value="">-- Selecciona torneo --</option>

        <?php foreach ($torneos as $t): ?>
            <option value="<?= $t['ID_Torneo'] ?>"
                <?= (isset($_GET['torneo']) && $_GET['torneo'] == $t['ID_Torneo']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($t['Nombre']) ?>
            </option>
        <?php endforeach; ?>

    </select>
</form>

<?php if (empty($torneos)): ?>
    <p>No hay torneos.</p>
<?php else: ?>

<hr>

<?php if (empty($tabla)): ?>
    <p>No hay clasificación para este torneo.</p>
<?php else: ?>

<table border="1">
    <tr>
        <th>Jugador</th>
        <th>Puntos</th>
        <th>Victorias</th>
        <th>Derrotas</th>
    </tr>

    <?php foreach ($tabla as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['jugador']) ?></td>
            <td><?= $row['puntos'] ?></td>
            <td><?= $row['victorias'] ?></td>
            <td><?= $row['derrotas'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php endif; ?>
<?php endif; ?>

<?php include '../../includes/footer.php'; ?>