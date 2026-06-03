<?php
class JugadorController {
    private JugadorModel $model;

    public function __construct() {
        $this->model = new JugadorModel();
    }

    public function index(): array {
        return $this->model->obtenerTodos();
    }
}