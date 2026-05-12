<?php
// admin/logout.php
require_once __DIR__ . '/../config.php';
Auth::iniciarSesion();
Auth::cerrarSesion();
header('Location: ' . APP_URL . '/admin/login.php?msg=logout');
exit;
