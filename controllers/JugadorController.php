<?php
// controllers/JugadorController.php
class JugadorController {

    private JugadorModel $model;

    public function __construct() {
        $this->model = new JugadorModel();
    }

    public function index(): void {
        $busqueda  = trim($_GET['q'] ?? '');
        $jugadores = $busqueda !== ''
            ? $this->model->buscar($busqueda)
            : $this->model->obtenerTodos();

        $tituloPagina = 'Jugadores — ' . APP_NAME;
        $paginaActiva = 'jugadores';
        include BASE_PATH . '/views/jugadores/index.php';
    }
}
