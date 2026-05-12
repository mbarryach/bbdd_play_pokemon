<?php
// ─────────────────────────────────────────────────────────
//  controllers/ClasificacionController.php
//
//  Responsabilidad: lógica entre el modelo y la vista.
//  Recoge datos del modelo, los procesa si hace falta,
//  y los pasa a la vista. NO genera HTML.
// ─────────────────────────────────────────────────────────

class ClasificacionController {

    private ClasificacionModel $model;

    public function __construct() {
        $this->model = new ClasificacionModel();
    }

    // ── Página principal de clasificación ──────────────
    public function index(): void {
        $tabla        = $this->model->obtenerTabla();
        $tituloPagina = 'Clasificación — ' . APP_NAME;
        $paginaActiva = 'clasificacion';

        include BASE_PATH . '/views/clasificacion/index.php';
    }
}
