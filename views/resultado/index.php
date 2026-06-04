<?php
include '../../includes/header.php'; 
require_once '../../controllers/ResultadoController.php';

$controller = new ResultadoController();
$cantidad = isset($_GET['cantidad']) ? (int)$_GET['cantidad'] : 10;
$resultados = $controller->ultimos($cantidad);

?>

<section class="page">
    <h2 class="section-title">Últimos resultados</h2>
    <form method="GET" action="" style="margin-bottom: 1rem;">
        <label for="cantidad"></label>
        <select name="cantidad" id="cantidad" onchange="this.form.submit()">
            <option value="10" <?= $cantidad == 10 ? 'selected' : '' ?>>10</option>
            <option value="20" <?= $cantidad == 20 ? 'selected' : '' ?>>20</option>
            <option value="50" <?= $cantidad == 50 ? 'selected' : '' ?>>50</option>
            <option value="999" <?= $cantidad == 999 ? 'selected' : '' ?>>max.</option>
        </select>
    </form>

    <?php if (empty($resultados)): ?>
        <p style="color:var(--texto);opacity:.6">No hay resultados todavía.</p>
    <?php else: ?>
        <div class="partidas-col">
            <?php foreach ($resultados as $p): ?>
            <div class="partido-card fade-in">
                <div class="partido-equipo local">
                    <span class="nombre <?= $p['ganador']===$p['jugador1']?'ganador':'' ?>">
                        <?= htmlspecialchars($p['jugador1']) ?>
                    </span>
                </div>
                <div class="partido-centro">
                    <span class="marcador">
                        <?= (int)$p['Juegos_Jugador1'] ?> – <?= (int)$p['Juegos_Jugador2'] ?>
                    </span>
                    <?php if ($p['ronda']): ?>
                    <span class="partido-ronda">Ronda <?= (int)$p['ronda'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="partido-equipo visitante">
                    <span class="nombre <?= $p['ganador']===$p['jugador2']?'ganador':'' ?>">
                        <?= htmlspecialchars($p['jugador2']) ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<style>

.page {
    margin: 40px 200px;
    text-align: center;
}

.partidas-col {
    margin-top: 40px;
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    justify-content: space-between;
}

.partido-card {
    display: flex;
    justify-content: space-between;
    background: rgba(255,255,255,0.03);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 10px 15px;

    /* clave para 2 columnas */
    width: calc(50% - 6px);
    box-sizing: border-box;
}
.partido-equipo {
    flex: 1;
}
.local { text-align: left; }
.visitante { text-align: right; }
.nombre.ganador {
    font-weight: bold;
    color: var(--verde);
}
.partido-centro {
    text-align: center;
    min-width: 80px;
}
.marcador {
    font-weight: bold;
    font-size: 1.2rem;
}
.partido-ronda {
    font-size: 0.7rem;
    color: var(--texto);
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

</style>

<?php include '../../includes/footer.php'; ?>