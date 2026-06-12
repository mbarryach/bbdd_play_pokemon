<?php
require_once '../../models/JugadorModelAdmin.php';

class JugadorControllerAdmin {
    private JugadorModelAdmin $model;

    public function __construct() {
        $this->model = new JugadorModelAdmin();
    }

    public function index(): array {
        return $this->model->obtenerTodos();
    }
    
    public function ultimos(int $limite = 10): array {
        return $this->model->obtenerTodos($limite);
    }

    // Métodos para cada acción (los llama la vista)
    public function sumarCP(int $id): bool {
        return $this->model->sumarCP($id, 10);
    }

    public function restarCP(int $id): bool {
        return $this->model->restarCP($id, 10);
    }

    public function eliminarJugador(int $id): bool {
    return $this->model->eliminarJugador($id);
    }

    public function aplicarSancion(int $id): bool {
        return $this->model->aplicarSancion($id, 20);
    }


}
?>