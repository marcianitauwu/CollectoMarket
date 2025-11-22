 
<?php
// models/Categoria.php
require_once __DIR__ . '/../config/db.php';

class Categoria {
    private $table_name = "categorias";
    private $conn;

    public $id;
    public $nombreCategoria;
    public $descripcion;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener todas las categorías (para llenar selects en formularios)
    public function obtenerTodas() {
        $query = "SELECT id, nombreCategoria, descripcion FROM " . $this->table_name . " ORDER BY nombreCategoria ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Devuelve el PDOStatement para iterar
    }

    // Obtener una categoría específica
    public function obtenerPorId($id) {
        $query = "SELECT id, nombreCategoria, descripcion FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>