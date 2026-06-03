<?php
class TorneoController {
    private TorneoModel $model;

    public function __construct() {
        $this->model = new TorneoModel();
    }

    public function index(): array {
        return $this->model->obtenerTodos();
    }
}
?>