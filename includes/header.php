<?php
// includes/header.php
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/../config.php';
}
$paginaActiva = $paginaActiva ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina ?? APP_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>../assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <a class="navbar-brand" href="<?= APP_URL ?>/index.php">
        ⚡ <?= htmlspecialchars(APP_NAME) ?>
    </a>
    <ul class="nav-links">
        <li><a href="<?= APP_URL ?>/index.php"           class="<?= $paginaActiva === 'inicio'         ? 'active' : '' ?>">Inicio</a></li>
        <li><a href="<?= APP_URL ?>/clasificacion.php"   class="<?= $paginaActiva === 'clasificacion'  ? 'active' : '' ?>">Clasificación</a></li>
        <li><a href="<?= APP_URL ?>/resultados.php"      class="<?= $paginaActiva === 'resultados'     ? 'active' : '' ?>">Resultados</a></li>
        <li><a href="<?= APP_URL ?>/equipos.php"         class="<?= $paginaActiva === 'equipos'        ? 'active' : '' ?>">Equipos</a></li>
    </ul>
</nav>

<main>
<div class="container">
