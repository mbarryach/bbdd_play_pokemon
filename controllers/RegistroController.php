<?php
// ─────────────────────────────────────────────────────────
//  controllers/RegistroController.php
//
//  El registro público SOLO crea usuarios con rol 'consulta'.
//  Cualquier intento de elegir otro rol desde el formulario
//  es ignorado: el modelo fija siempre 'consulta'.
// ─────────────────────────────────────────────────────────

class RegistroController {

    private UsuarioModel $model;

    public function __construct() {
        $this->model = new UsuarioModel();
    }

    public function index(): void {
        Auth::iniciarSesion();

        // Si ya está autenticado, redirige al panel
        if (Auth::check()) {
            header('Location: ' . APP_URL . '/admin/dashboard.php');
            exit;
        }

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario  = trim($_POST['usuario']   ?? '');
            $password = $_POST['password']        ?? '';
            $confirm  = $_POST['password_confirm'] ?? '';

            // ── Validaciones ───────────────────────────
            if ($usuario === '' || $password === '') {
                $error = 'Usuario y contraseña son obligatorios.';
            } elseif (strlen($usuario) < 3 || strlen($usuario) > 30) {
                $error = 'El usuario debe tener entre 3 y 30 caracteres.';
            } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $usuario)) {
                $error = 'El usuario solo puede contener letras, números y guion bajo.';
            } elseif (strlen($password) < 8) {
                $error = 'La contraseña debe tener al menos 8 caracteres.';
            } elseif ($password !== $confirm) {
                $error = 'Las contraseñas no coinciden.';
            } elseif ($this->model->existeUsuario($usuario)) {
                $error = 'Ese nombre de usuario ya está en uso.';
            } else {
                // registrarPublico() asigna rol 'consulta' internamente.
                // No se pasa ni se acepta ningún rol del formulario.
                if ($this->model->registrarPublico($usuario, $password)) {
                    $success = '¡Cuenta creada! Ya puedes iniciar sesión.';
                } else {
                    $error = 'Error al crear la cuenta. Inténtalo de nuevo.';
                }
            }
        }

        $tituloPagina = 'Registro — ' . APP_NAME;

        include BASE_PATH . '/views/registro.php';
    }
}
