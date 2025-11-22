 
<?php
// models/Chat.php
require_once __DIR__ . '/../config/db.php';

class Chat {
    private $table_name = "chats";
    private $conn;

    public $id;
    public $id_producto;
    public $id_comprador;
    public $id_vendedor;
    public $fechaCreacion;
    // Estado añadido según diagrama
    public $estado; 

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Iniciar un nuevo chat (verifica si ya existe uno para ese producto y usuarios)
    public function iniciarConversacion($id_producto, $id_comprador, $id_vendedor) {
        // 1. Verificar si ya existe
        $checkQuery = "SELECT id FROM " . $this->table_name . " 
                       WHERE id_producto = :p AND id_comprador = :c AND id_vendedor = :v LIMIT 1";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->execute([':p' => $id_producto, ':c' => $id_comprador, ':v' => $id_vendedor]);
        
        if ($checkStmt->rowCount() > 0) {
            // Ya existe, devolver el ID del chat existente
            return $checkStmt->fetch(PDO::FETCH_ASSOC)['id'];
        }

        // 2. Si no existe, crear nuevo
        $query = "INSERT INTO " . $this->table_name . " 
                  SET id_producto=:id_producto, id_comprador=:id_comprador, id_vendedor=:id_vendedor, fechaCreacion=NOW()";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_producto", $id_producto);
        $stmt->bindParam(":id_comprador", $id_comprador);
        $stmt->bindParam(":id_vendedor", $id_vendedor);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Obtener todos los chats donde participa un usuario (panel de Mensajes)
    public function obtenerChatsDeUsuario($id_usuario) {
        // Esta consulta compleja obtiene el chat y determina quién es el "otro" usuario
        $query = "SELECT c.*, p.titulo as producto_titulo, p.url_imagen_principal as producto_imagen,
                    CASE 
                        WHEN c.id_comprador = :id_usuario THEN uv.nombreUsuario 
                        ELSE uc.nombreUsuario 
                    END as otro_usuario_nombre,
                    CASE 
                        WHEN c.id_comprador = :id_usuario THEN uv.avatar_url 
                        ELSE uc.avatar_url 
                    END as otro_usuario_avatar
                  FROM " . $this->table_name . " c
                  JOIN productos p ON c.id_producto = p.id
                  JOIN usuarios uc ON c.id_comprador = uc.id
                  JOIN usuarios uv ON c.id_vendedor = uv.id
                  WHERE c.id_comprador = :id_usuario OR c.id_vendedor = :id_usuario
                  ORDER BY c.fechaCreacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->execute();
        return $stmt;
    }

    // Obtener detalles de un chat específico
    public function obtenerPorId($id_chat) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id_chat);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>