<?php
// ─────────────────────────────────────────────────────────
//  controllers/TorneoController.php
//
//  index()  → página pública de torneos
//  admin()  → gestión (solo admin/árbitro)
//  crear()  → formulario alta (solo admin)
// ─────────────────────────────────────────────────────────

class TorneoController {

    private TorneoModel $model;

    public function __construct() {
        $this->model = new TorneoModel();
    }

    // ── Página pública ──────────────────────────────────
    public function index(): void {
        $torneos      = $this->model->obtenerTodos();
        $tituloPagina = 'Torneos — ' . APP_NAME;
        $paginaActiva = 'torneos';
        include BASE_PATH . '/views/torneos/index.php';
    }

    // ── Panel admin: listado + acciones ────────────────
    public function admin(): void {
        Auth::requerirRol([ROL_ADMIN, ROL_ARBITRO]);
        $accion = $_GET['accion'] ?? '';

        match($accion) {
            'crear'   => $this->crear(),
            'editar'  => $this->editar((int)($_GET['id'] ?? 0)),
            'eliminar'=> $this->eliminar((int)($_GET['id'] ?? 0)),
            default   => $this->listarAdmin(),
        };
    }

    // ── Listado admin ───────────────────────────────────
    private function listarAdmin(): void {
        $torneos      = $this->model->obtenerTodos();
        $msg          = $_GET['msg'] ?? '';
        $paginaAdmin  = 'torneos';
        $tituloPagina = 'Gestión de torneos — ' . APP_NAME;
        include BASE_PATH . '/admin/views/torneos.php';
    }

    // ── Crear torneo ────────────────────────────────────
    private function crear(): void {
        Auth::requerirRol([ROL_ADMIN]);
        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            if ($nombre === '') {
                $error = 'El nombre del torneo es obligatorio.';
            } else {
                $id = $this->model->crear([
                    'nombre'       => $nombre,
                    'tipo'         => $_POST['tipo']         ?? 'Suizo',
                    'fecha_inicio' => $_POST['fecha_inicio'] ?? null,
                    'fecha_fin'    => $_POST['fecha_fin']    ?? null,
                    'ubicacion'    => $_POST['ubicacion']    ?? null,
                    'pais'         => $_POST['pais']         ?? null,
                    'rondas'       => $_POST['rondas']       ?? 0,
                    'top_cut'      => $_POST['top_cut']      ?? 0,
                ]);
                if ($id) {
                    header('Location: ' . APP_URL . '/admin/torneos.php?msg=creado');
                    exit;
                }
                $error = 'Error al crear el torneo.';
            }
        }

        $paginaAdmin  = 'torneos';
        $tituloPagina = 'Nuevo torneo — ' . APP_NAME;
        include BASE_PATH . '/admin/views/crear_torneo.php';
    }

    // ── Editar torneo ───────────────────────────────────
    private function editar(int $id): void {
        Auth::requerirRol([ROL_ADMIN]);
        $torneo = $this->model->obtenerPorId($id);
        if (!$torneo) {
            header('Location: ' . APP_URL . '/admin/torneos.php');
            exit;
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            if ($nombre === '') {
                $error = 'El nombre es obligatorio.';
            } else {
                $this->model->editar($id, [
                    'nombre'       => $nombre,
                    'tipo'         => $_POST['tipo']         ?? $torneo['Tipo_Torneo'],
                    'fecha_inicio' => $_POST['fecha_inicio'] ?? $torneo['Fecha_Inicio'],
                    'fecha_fin'    => $_POST['fecha_fin']    ?? $torneo['Fecha_Fin'],
                    'ubicacion'    => $_POST['ubicacion']    ?? $torneo['Ubicacion'],
                    'pais'         => $_POST['pais']         ?? $torneo['Pais'],
                    'rondas'       => $_POST['rondas']       ?? $torneo['Num_Rondas_Suizas'],
                    'top_cut'      => $_POST['top_cut']      ?? $torneo['Tamanio_Top_Cut'],
                ]);
                header('Location: ' . APP_URL . '/admin/torneos.php?msg=editado');
                exit;
            }
        }

        $paginaAdmin  = 'torneos';
        $tituloPagina = 'Editar torneo — ' . APP_NAME;
        include BASE_PATH . '/admin/views/crear_torneo.php';
    }

    // ── Eliminar torneo ─────────────────────────────────
    private function eliminar(int $id): void {
        Auth::requerirRol([ROL_ADMIN]);
        $this->model->eliminar($id);
        header('Location: ' . APP_URL . '/admin/torneos.php?msg=eliminado');
        exit;
    }
}
