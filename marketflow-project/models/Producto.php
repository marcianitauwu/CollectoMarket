 
<?php
// models/Producto.php
require_once __DIR__ . '/../config/db.php';

class Producto {
    private $table_name = "productos";
    private $conn;

    public $id;
    public $titulo;
    public $descripcion;
    public $precio;
    public $estado; // Nuevo/Usado
    public $fechaPublicacion;
    public $id_usuario;
    public $id_categoria;
    public $disponible;
    public $url_imagen_principal;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Crear una nueva publicación
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET titulo=:titulo, descripcion=:descripcion, precio=:precio, estado=:estado, 
                      id_usuario=:id_usuario, id_categoria=:id_categoria, disponible=1, 
                      url_imagen_principal=:url_imagen_principal, fechaPublicacion=NOW()";

        $stmt = $this->conn->prepare($query);

        // Validar/limpiar datos antes de vincular si es necesario
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));

        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->bindParam(":id_categoria", $this->id_categoria);
        $stmt->bindParam(":url_imagen_principal", $this->url_imagen_principal);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Obtener todos los productos para el catálogo (con JOINs)
    public function obtenerTodos() {
        $query = "SELECT p.*, u.nombreUsuario as vendedor, c.nombreCategoria 
                  FROM " . $this->table_name . " p
                  LEFT JOIN usuarios u ON p.id_usuario = u.id
                  LEFT JOIN categorias c ON p.id_categoria = c.id
                  WHERE p.disponible = 1
                  ORDER BY p.fechaPublicacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener detalles de un producto por ID
    public function obtenerPorId($id) {
        $query = "SELECT p.*, u.nombreUsuario as vendedor, u.avatar_url as avatar_vendedor, c.nombreCategoria 
                  FROM " . $this->table_name . " p
                  LEFT JOIN usuarios u ON p.id_usuario = u.id
                  LEFT JOIN categorias c ON p.id_categoria = c.id
                  WHERE p.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            // Llenar el objeto actual
            foreach ($row as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
            return $row; // Retornar el array completo con datos extra del JOIN
        }
        return false;
    }

    // Obtener productos de un usuario específico (Mis Publicaciones)
    public function obtenerPorUsuario($id_usuario) {
        $query = "SELECT p.*, c.nombreCategoria 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categorias c ON p.id_categoria = c.id
                  WHERE p.id_usuario = :id_usuario ORDER BY p.fechaPublicacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->execute();
        return $stmt;
    }
}
?>