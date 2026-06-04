<?php
class ResultadoController {
    private ResultadoModel $model;

    public function __construct() {
        $this->model = new ResultadoModel();
    }

    public function ultimos(int $limite = 10): array {
        return $this->model->obtenerUltimos($limite);
    }
}
?>