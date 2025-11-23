<?php
// Credenciales de la Base de Datos
$servername = "localhost";
$username = "root"; // Usuario por defecto de XAMPP/MariaDB
$password = "";     // Contraseña por defecto de XAMPP (suele ser vacía)
$dbname = "collectomart_db"; // Nombre de la base de datos

// Intentar la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Opcional: Establecer juego de caracteres a UTF8
$conn->set_charset("utf8");

// NOTA: Para usar este archivo en cualquier página, solo necesitas:
// require_once 'config/db.php';
?>