<?php
// controllers/ClasificacionController.php
class ClasificacionController {

    private ClasificacionModel $modelClasif;
    private TorneoModel        $modelTorneo;

    public function __construct() {
        $this->modelClasif = new ClasificacionModel();
        $this->modelTorneo = new TorneoModel();
    }

    public function index(): void {
        $torneos  = $this->modelTorneo->obtenerLista();
        $torneoId = (int)($_GET['torneo'] ?? 0);

        if ($torneoId === 0) {
            $activo   = $this->modelTorneo->obtenerActivo();
            $torneoId = $activo ? (int)$activo['ID_Torneo'] : 0;
        }

        $torneoActual = $torneoId > 0 ? $this->modelTorneo->obtenerPorId($torneoId) : null;
        $tabla        = $torneoId > 0 ? $this->modelClasif->obtenerPorTorneo($torneoId) : [];

        $tituloPagina = 'Clasificación — ' . APP_NAME;
        $paginaActiva = 'clasificacion';

        include BASE_PATH . '/views/clasificacion/index.php';
    }
}
