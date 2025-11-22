 
<?php
// models/Mensaje.php
require_once __DIR__ . '/../config/db.php';

class Mensaje {
    private $table_name = "mensajes";
    private $conn;

    public $id;
    public $id_chat;
    public $id_remitente;
    public $contenido;
    public $fechaHoraEnvio;
    public $leido;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Enviar un nuevo mensaje
    public function enviar($id_chat, $id_remitente, $contenido) {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET id_chat=:id_chat, id_remitente=:id_remitente, contenido=:contenido, fechaEnvio=NOW(), leido=0";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar contenido del mensaje
        $this->contenido = htmlspecialchars(strip_tags($contenido));

        $stmt->bindParam(":id_chat", $id_chat);
        $stmt->bindParam(":id_remitente", $id_remitente);
        $stmt->bindParam(":contenido", $this->contenido);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Obtener historial de mensajes de un chat
    public function obtenerMensajesDeChat($id_chat) {
        $query = "SELECT m.*, u.nombreUsuario as remitente_nombre, u.avatar_url as remitente_avatar
                  FROM " . $this->table_name . " m
                  JOIN usuarios u ON m.id_remitente = u.id
                  WHERE m.id_chat = :id_chat
                  ORDER BY m.fechaEnvio ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_chat", $id_chat);
        $stmt->execute();
        return $stmt;
    }
}
?>