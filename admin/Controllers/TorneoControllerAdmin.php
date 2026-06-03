<?php
require_once '../../models/TorneoModelAdmin.php';

class TorneoControllerAdmin {
    private TorneoModelAdmin $model;

    public function __construct() {
        $this->model = new TorneoModelAdmin();
    }

    public function index(): array {
        return $this->model->obtenerTodos();
    }

    public function getTemporadas(): array {
        return $this->model->obtenerTemporadas();
    }

    public function crear(array $datos): bool {
        return $this->model->crear($datos);
    }

    public function eliminar(int $id): bool {
        return $this->model->eliminar($id);
    }
}
?>