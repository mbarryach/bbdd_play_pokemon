<?php
// ─────────────────────────────────────────────────────────
//  admin/login.php
// ─────────────────────────────────────────────────────────
require_once __DIR__ . '/../config.php';
Auth::iniciarSesion();

if (Auth::check()) {
    header('Location: ' . APP_URL . '/admin/dashboard.php');
    exit;
}

$error = '';
$msg   = $_GET['msg'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario  = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($usuario === '' || $password === '') {
        $error = 'Usuario y contraseña son obligatorios.';
    } elseif (Auth::login($usuario, $password)) {
        header('Location: ' . APP_URL . '/admin/dashboard.php');
        exit;
    } else {
        $error = 'Credenciales incorrectas. Inténtalo de nuevo.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso — <?= htmlspecialchars(APP_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #07070f;
            --card:      #0f0f1a;
            --border:    #1e1e30;
            --amarillo:  #f5c518;
            --purpura:   #7c5cfc;
            --texto:     #b0b0c8;
            --blanco:    #eeeef8;
            --rojo:      #ff4d6d;
            --verde:     #06d6a0;
        }

        html, body {
            height: 100%;
            font-family: 'Nunito', sans-serif;
            background: var(--bg);
            color: var(--blanco);
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow: hidden;
        }

        /* ── Fondo animado ── */
        .bg-grid {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(124,92,252,.07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(124,92,252,.07) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: 0;
        }

        .bg-glow {
            position: fixed;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            filter: blur(120px);
            opacity: .18;
            z-index: 0;
        }
        .bg-glow.top    { top: -200px; left: -100px; background: var(--purpura); }
        .bg-glow.bottom { bottom: -200px; right: -100px; background: var(--amarillo); }

        /* ── Card ── */
        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 1rem;
        }

        .login-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            box-shadow: 0 0 60px rgba(124,92,252,.12), 0 24px 48px rgba(0,0,0,.5);
            animation: slideUp .5s ease both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Logo / título ── */
        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-pokeball {
            font-size: 3rem;
            display: block;
            margin-bottom: .75rem;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-8px); }
        }

        .login-titulo {
            font-family: 'Orbitron', sans-serif;
            font-size: .9rem;
            font-weight: 900;
            letter-spacing: .2em;
            color: var(--amarillo);
            text-transform: uppercase;
        }

        .login-subtitulo {
            font-size: .75rem;
            color: var(--texto);
            letter-spacing: .12em;
            margin-top: .25rem;
        }

        /* ── Alertas ── */
        .alert {
            border-radius: 10px;
            padding: .75rem 1rem;
            font-size: .85rem;
            margin-bottom: 1.25rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .alert-error   { background: rgba(255,77,109,.12); border: 1px solid rgba(255,77,109,.35); color: #ff8fa3; }
        .alert-warning { background: rgba(245,197,24,.10); border: 1px solid rgba(245,197,24,.3);  color: var(--amarillo); }
        .alert-success { background: rgba(6,214,160,.10);  border: 1px solid rgba(6,214,160,.3);   color: var(--verde); }

        /* ── Formulario ── */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .15em;
            color: var(--texto);
            text-transform: uppercase;
            margin-bottom: .5rem;
        }

        .form-control {
            width: 100%;
            background: rgba(255,255,255,.04);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: .75rem 1rem;
            color: var(--blanco);
            font-family: 'Nunito', sans-serif;
            font-size: .95rem;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--purpura);
            box-shadow: 0 0 0 3px rgba(124,92,252,.18);
        }

        .form-control::placeholder { color: rgba(176,176,200,.4); }

        /* ── Botón ── */
        .btn-login {
            width: 100%;
            padding: .85rem;
            background: linear-gradient(135deg, var(--purpura), #5a3fd8);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-family: 'Orbitron', sans-serif;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .18em;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s;
            margin-top: .5rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(124,92,252,.4);
        }

        .btn-login:active { transform: translateY(0); }

        /* ── Roles info ── */
        .roles-hint {
            margin-top: 1.75rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--border);
        }

        .roles-hint p {
            font-size: .7rem;
            color: var(--texto);
            opacity: .5;
            text-align: center;
            letter-spacing: .08em;
            margin-bottom: .75rem;
        }

        .roles-badges {
            display: flex;
            justify-content: center;
            gap: .5rem;
            flex-wrap: wrap;
        }

        .role-badge {
            padding: .3rem .7rem;
            border-radius: 20px;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .1em;
        }

        .role-badge.admin    { background: rgba(245,197,24,.12); color: var(--amarillo); border: 1px solid rgba(245,197,24,.3); }
        .role-badge.arbitro  { background: rgba(124,92,252,.12); color: #a78bfa;         border: 1px solid rgba(124,92,252,.3); }
        .role-badge.consulta { background: rgba(6,214,160,.10);  color: var(--verde);    border: 1px solid rgba(6,214,160,.3); }
    </style>
</head>
<body>

<div class="bg-grid"></div>
<div class="bg-glow top"></div>
<div class="bg-glow bottom"></div>

<div class="login-wrapper">
    <div class="login-card">

        <div class="login-logo">
            <span class="login-pokeball">⚡</span>
            <div class="login-titulo"><?= htmlspecialchars(APP_NAME) ?></div>
            <div class="login-subtitulo">Panel de Control</div>
        </div>

        <?php if ($msg === 'sesion_expirada'): ?>
            <div class="alert alert-warning">⏱ Tu sesión ha expirado. Vuelve a iniciar sesión.</div>
        <?php endif; ?>

        <?php if ($msg === 'logout'): ?>
            <div class="alert alert-success">✓ Sesión cerrada correctamente.</div>
        <?php endif; ?>

        <?php if ($msg === 'sin_permiso'): ?>
            <div class="alert alert-error">🚫 No tienes permiso para acceder a esa sección.</div>
        <?php endif; ?>

        <?php if ($error !== ''): ?>
            <div class="alert alert-error">✕ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="" autocomplete="on">
            <div class="form-group">
                <label class="form-label" for="username">Usuario</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-control"
                    placeholder="Introduce tu usuario"
                    autocomplete="username"
                    autofocus
                    required
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="••••••••••"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" class="btn-login">ENTRAR →</button>
        </form>

        <div class="roles-hint">
            <p>NIVELES DE ACCESO</p>
            <div class="roles-badges">
                <span class="role-badge admin">👑 Administrador</span>
                <span class="role-badge arbitro">⚖️ Árbitro</span>
                <span class="role-badge consulta">👁️ Consulta</span>
            </div>
        </div>

    </div>
</div>

</body>
</html>
