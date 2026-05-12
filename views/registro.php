<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
    <style>
        body { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; }
        .reg-card {
            background: var(--card); border: 1px solid var(--border); border-radius: 18px;
            padding: 2.25rem 2rem; width: 100%; max-width: 420px; box-shadow: 0 12px 40px rgba(0,0,0,.4);
        }
        .reg-titulo { font-family: 'Orbitron', sans-serif; font-size: .85rem; font-weight: 900;
                      letter-spacing: .18em; color: var(--amarillo); margin-bottom: 1.5rem; text-align: center; }
        .form-group { display: flex; flex-direction: column; gap: .4rem; margin-bottom: 1.1rem; }
        .form-label { font-size: .7rem; font-weight: 700; letter-spacing: .12em; color: var(--texto); text-transform: uppercase; }
        .form-control {
            background: rgba(255,255,255,.04); border: 1px solid var(--border); border-radius: 10px;
            padding: .65rem .9rem; color: var(--blanco); font-family: 'Nunito', sans-serif;
            font-size: .9rem; outline: none; transition: border-color .2s;
        }
        .form-control:focus { border-color: var(--purpura); }
        .form-hint { font-size: .7rem; color: var(--texto); opacity: .7; }
        .btn-submit {
            width: 100%; padding: .8rem; background: var(--purpura); border: none; border-radius: 10px;
            color: #fff; font-family: 'Orbitron', sans-serif; font-size: .72rem; font-weight: 700;
            letter-spacing: .15em; cursor: pointer; transition: background .15s, transform .15s; margin-top: .25rem;
        }
        .btn-submit:hover { background: #6a4de0; transform: translateY(-1px); }
        .alert { padding: .75rem 1rem; border-radius: 10px; font-size: .85rem; font-weight: 600; margin-bottom: 1rem; }
        .alert-error   { background: rgba(255,77,109,.12); border: 1px solid rgba(255,77,109,.3); color: #ff8fa3; }
        .alert-success { background: rgba(6,214,160,.10);  border: 1px solid rgba(6,214,160,.3);  color: var(--verde); }
        .reg-footer { text-align: center; margin-top: 1.25rem; font-size: .82rem; color: var(--texto); }
        .reg-footer a { color: var(--purpura); font-weight: 700; }
        /* Nota de seguridad: no hay campo de rol en este formulario.
           El controlador asigna 'consulta' forzosamente en el backend. */
    </style>
</head>
<body>

<div class="reg-card">
    <div class="reg-titulo">⚡ Crear cuenta</div>

    <?php if ($error !== ''): ?>
        <div class="alert alert-error">✕ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success !== ''): ?>
        <div class="alert alert-success">
            ✓ <?= htmlspecialchars($success) ?>
            <br><a href="<?= APP_URL ?>/admin/login.php" style="color:inherit;text-decoration:underline;">→ Iniciar sesión</a>
        </div>
    <?php else: ?>

    <form method="POST" action="">
        <div class="form-group">
            <label class="form-label" for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" class="form-control"
                   placeholder="solo letras, números y _"
                   value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>"
                   maxlength="30" required autocomplete="username">
            <span class="form-hint">Entre 3 y 30 caracteres. Solo letras, números y guion bajo.</span>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Contraseña</label>
            <input type="password" id="password" name="password" class="form-control"
                   placeholder="mínimo 8 caracteres"
                   maxlength="72" required autocomplete="new-password">
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirm">Repite la contraseña</label>
            <input type="password" id="password_confirm" name="password_confirm" class="form-control"
                   placeholder="••••••••"
                   maxlength="72" required autocomplete="new-password">
        </div>

        <!--
            SEGURIDAD: no hay campo "rol" aquí.
            Cualquier parámetro rol enviado por POST es ignorado.
            El rol 'consulta' lo asigna el servidor en UsuarioModel::registrarPublico().
        -->

        <button type="submit" class="btn-submit">CREAR CUENTA →</button>
    </form>

    <?php endif; ?>

    <div class="reg-footer">
        ¿Ya tienes cuenta? <a href="<?= APP_URL ?>/admin/login.php">Iniciar sesión</a>
    </div>
</div>

</body>
</html>
