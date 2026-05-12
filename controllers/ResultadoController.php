<?php
// ─────────────────────────────────────────────────────────
//  controllers/ResultadoController.php
// ─────────────────────────────────────────────────────────

class ResultadoController {

    private ResultadoModel $model;

    public function __construct() {
        $this->model = new ResultadoModel();
    }

    // ── Página pública de resultados ───────────────────
    public function index(): void {
        $pagina    = max(1, (int)($_GET['p'] ?? 1));
        $porPagina = 15;

        $resultados   = $this->model->obtenerTodos($pagina, $porPagina);
        $totalItems   = $this->model->contarTotal();
        $totalPaginas = (int) ceil($totalItems / $porPagina);

        $tituloPagina = 'Resultados — ' . APP_NAME;
        $paginaActiva = 'resultados';

        include BASE_PATH . '/views/resultados/index.php';
    }

    // ── Formulario de edición (árbitro / admin) ────────
    public function editar(int $id): void {
        Auth::requerirRol([ROL_ADMIN, ROL_ARBITRO]);

        $partido = $this->model->obtenerPorId($id);
        if (!$partido) {
            header('Location: ' . APP_URL . '/admin/dashboard.php');
            exit;
        }

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $gl = (int)($_POST['goles_local']     ?? 0);
            $gv = (int)($_POST['goles_visitante'] ?? 0);

            if ($this->model->registrarResultado($id, $gl, $gv)) {
                $success = 'Resultado guardado correctamente.';
                $partido = $this->model->obtenerPorId($id); // recargar
            } else {
                $error = 'Error al guardar. Verifica los datos.';
            }
        }

        $tituloPagina = 'Editar resultado — ' . APP_NAME;
        $paginaAdmin  = 'resultados';

        include BASE_PATH . '/admin/views/editar_resultado.php';
    }
}
