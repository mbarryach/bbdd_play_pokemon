<?php
// controllers/ResultadoController.php
class ResultadoController {

    private ResultadoModel $model;
    private TorneoModel    $modelTorneo;

    public function __construct() {
        $this->model       = new ResultadoModel();
        $this->modelTorneo = new TorneoModel();
    }

    public function index(): void {
        $torneos  = $this->modelTorneo->obtenerLista();
        $torneoId = (int)($_GET['torneo'] ?? 0);

        if ($torneoId === 0) {
            $activo   = $this->modelTorneo->obtenerActivo();
            $torneoId = $activo ? (int)$activo['ID_Torneo'] : 0;
        }

        $pagina    = max(1, (int)($_GET['p'] ?? 1));
        $porPagina = 12;

        if ($torneoId > 0) {
            $resultados   = $this->model->obtenerPorTorneo($torneoId, $pagina, $porPagina);
            $totalItems   = $this->model->contarPorTorneo($torneoId);
        } else {
            $resultados   = $this->model->obtenerTodos($pagina, $porPagina);
            $totalItems   = $this->model->contarTotal();
        }

        $totalPaginas = $totalItems > 0 ? (int)ceil($totalItems / $porPagina) : 1;
        $torneoActual = $torneoId > 0 ? $this->modelTorneo->obtenerPorId($torneoId) : null;

        $tituloPagina = 'Resultados — ' . APP_NAME;
        $paginaActiva = 'resultados';
        include BASE_PATH . '/views/resultados/index.php';
    }
}
