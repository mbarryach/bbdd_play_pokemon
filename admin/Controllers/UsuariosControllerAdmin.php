<?php
require_once '../../models/UsuariosModelAdmin.php';

class UsuarioController {
    private UsuarioModel $model;

    public function __construct() {
        $this->model = new UsuarioModel();
    }

    public function index(): array {
        return $this->model->obtenerTodos();
    }
}
?>