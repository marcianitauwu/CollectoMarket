<?php
require_once __DIR__ . '/../config/db.php';

class Usuario{
    private $table_name="usuarios";
    private $conn;

    public $id;
    public $nombreUsuario;
    public $correoElectronico;
    private $contrasena;
    public $fechaRegistro;
    public $avatar_url;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    public function registrarse($nombre, $correo, $contrasena, $avatar_url = null) {

    // Asignar propiedades
    $this->nombreUsuario = $nombre;
    $this->correoElectronico = $correo;
    $this->contrasena = $contrasena;
    $this->avatar_url = $avatar_url;

    // Hashear contraseña
    $hashed_password = password_hash($this->contrasena, PASSWORD_DEFAULT);

    $query = "INSERT INTO " . $this->table_name . " 
              SET nombreUsuario=:nombreUsuario, 
                  correoElectronico=:correoElectronico, 
                  contrasena=:contrasena, 
                  fechaRegistro=NOW(), 
                  avatar_url=:avatar_url";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":nombreUsuario", $this->nombreUsuario);
    $stmt->bindParam(":correoElectronico", $this->correoElectronico);  // ← corregido
    $stmt->bindParam(":contrasena", $hashed_password);
    $stmt->bindParam(":avatar_url", $this->avatar_url);

    if ($stmt->execute()) {
        $this->id = $this->conn->lastInsertId();
        return true;
    }

    return false;
}

    
    
    public function iniciarSesion($correo, $contrasenaIngresada) {
        // Buscar el usuario por correo
        $query = "SELECT id, nombreUsuario, correoElectronico, contrasena, fechaRegistro, avatar_url 
              FROM " . $this->table_name . " 
              WHERE correoElectronico = :correoElectronico 
              LIMIT 1";
              
              $stmt = $this->conn->prepare($query);
              $stmt->bindParam(":correoElectronico", $correo);
              
              $stmt->execute();
              
              // Ver si existe el usuario
              if ($stmt->rowCount() == 1) {
                  $row = $stmt->fetch(PDO::FETCH_ASSOC);
                  
                  // Verificar contraseña
                  if (password_verify($contrasenaIngresada, $row['contrasena'])) {
                      
                      // Cargar los datos en el objeto
                      $this->id = $row['id'];
                      $this->nombreUsuario = $row['nombreUsuario'];
                      $this->correoElectronico = $row['correoElectronico'];
                      $this->avatar_url = $row['avatar_url'];
                      $this->fechaRegistro = $row['fechaRegistro'];
                      
                      return true; // inicio de sesión exitoso
                    }
                }
                
                return false; // correo incorrecto o contraseña incorrecta
            }
            
            
            
        }
            ?>
