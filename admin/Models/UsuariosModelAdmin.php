<?php
require_once '../../../clases/ConexionDB.php';  // ← Línea añadida

class UsuarioModel {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = ConexionDB::getInstancia()->getConexion();
    }

    public function obtenerTodos(): array {
        $stmt = $this->pdo->query("
            SELECT usuario, password, rol, created_at
            FROM usuarios
            ORDER BY rol ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>