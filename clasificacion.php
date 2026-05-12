<?php
// ─────────────────────────────────────────────────────────
//  clasificacion.php — Página pública
//  Solo carga config y delega todo al controlador.
// ─────────────────────────────────────────────────────────
require_once __DIR__ . '/config.php';

$ctrl = new ClasificacionController();
$ctrl->index();
