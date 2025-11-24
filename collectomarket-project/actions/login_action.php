<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $username = trim($_POST['username']);
   $password = $_POST['password'];

   // 1. Obtener la contraseña hasheada del usuario
   $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
   $stmt->bind_param("s", $username);
   $stmt->execute();
   $result = $stmt->get_result();

   if ($result->num_rows === 1) {
      $user = $result->fetch_assoc();
        
      // 2. Verificar la contraseña (Criterio: Acceso exitoso)
      if (password_verify($password, $user['password'])) {
         // Credenciales válidas: Iniciar sesión
         $_SESSION['user_id'] = $user['id'];
         $_SESSION['username'] = $user['username'];
            
         // Redirigir al catálogo/home
         header("Location: ../index.php");
         exit;
            
      } else {
         // Contraseña incorrecta (Criterio: Credenciales incorrectas)
         $_SESSION['message'] = "Contraseña incorrecta. Por favor, inténtalo de nuevo.";
         header("Location: ../login.php");
         exit;
      }
   } else {
      // Usuario no encontrado
      $_SESSION['message'] = "El usuario no existe o las credenciales no son válidas.";
      header("Location: ../login.php");
      exit;
   }

   $stmt->close();
   $conn->close();
} else {
   // Si se accede a este archivo sin un POST, redirigir
   header("Location: ../login.php");
   exit;
}
// NOTA: Se omite la etiqueta de cierre ?> para evitar errores de salida.