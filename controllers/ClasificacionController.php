<?php
class ClasificacionController {

    private ClasificacionModel $modelClasif;
    private TorneoModel $modelTorneo;

    public function __construct() {
        $this->modelClasif = new ClasificacionModel();
        $this->modelTorneo = new TorneoModel();
    }

    public function index(): void {

        $torneos = $this->modelTorneo->obtenerLista();

        $torneoId = isset($_GET['torneo']) ? (int)$_GET['torneo'] : 0;

        $torneoActual = null;
        $tabla = [];
        
        if ($torneoId > 0) {
            $torneoActual = $this->modelTorneo->obtenerPorId($torneoId);
            $tabla = $this->modelClasif->obtenerPorTorneo($torneoId);
        }
    }
    public function getTorneos(): array {
        return $this->modelTorneo->obtenerLista();
    }
    
    public function getClasificacionByTorneo(int $torneoId): array {
        return $this->modelClasif->obtenerPorTorneo($torneoId);
    }

}