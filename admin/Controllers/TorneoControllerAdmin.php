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
}
?>