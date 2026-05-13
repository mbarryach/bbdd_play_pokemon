<?php
require_once __DIR__ . '/config.php';

$usuario  = 'admin';
$password = 'Admin1234!';   // Cambia esto por tu contraseña

try {
    $pdo  = ConexionDB::getInstancia()->getConexion();

    // Verificar si ya existe
    $check = $pdo->prepare('SELECT id FROM usuarios WHERE usuario = ?');
    $check->execute([$usuario]);

    if ($check->fetch()) {
        echo '⚠️ El usuario ya existe.';
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $pdo->prepare(
            'INSERT INTO usuarios (usuario, password) VALUES (?, ?)'
        );
        $stmt->execute([$usuario, $hash]);
        echo '✅ Admin creado correctamente.<br>';
        echo 'Usuario: <strong>' . $usuario . '</strong><br>';
        echo 'Hash generado: <code>' . $hash . '</code>';
    }
} catch (Exception $e) {
    echo '❌ Error: ' . $e->getMessage();
}


//Tienes que adaptar esto a la creacion de usuarios dentro de la web, y crear los usuarios con rol administrador en la base de datos directamente
