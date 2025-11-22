<?php
// Iniciar sesión PHP para guardar el estado si el login es correcto
session_start();

// Incluir el modelo (ajusta la ruta si es necesario)
require_once __DIR__ . '/../models/Usuario.php';

$mensaje = "";

// Si el formulario se ha enviado por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $email = isset($_POST['correo']) ? trim($_POST['correo']) : "";
    $password = isset($_POST['contrasena']) ? $_POST['contrasena'] : "";

    if (empty($email) || empty($password)) {
        $mensaje = "Por favor, rellena ambos campos.";
    } else {
        // Instanciar el usuario
        $usuario = new Usuario();

        // Intentar iniciar sesión
        if ($usuario->iniciarSesion($email, $password)) {
            // Login exitoso. Guardamos datos en la sesión.
            $_SESSION['usuario_id'] = $usuario->id;
            $_SESSION['usuario_nombre'] = $usuario->nombreUsuario;
            
            // Opcional: Redirigir a otra página (ej. dashboard.php)
             header("Location: catalog.php");
         exit();

            $mensaje = "Login correcto. Bienvenido " . $usuario->nombreUsuario;
        } else {
            $mensaje = "Credenciales incorrectas.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Sencillo</title>
</head>
<body>

    <h2>Iniciar Sesión</h2>

    <?php if ($mensaje): ?>
        <p><strong><?php echo $mensaje; ?></strong></p>
    <?php endif; ?>

    <form action="" method="POST">
        <div>
            <label for="correo">Correo Electrónico:</label><br>
            <input type="email" id="correo" name="correo" required>
        </div>
        <br>
        <div>
            <label for="contrasena">Contraseña:</label><br>
            <input type="password" id="contrasena" name="contrasena" required>
        </div>
        <br>
        <button type="submit">Entrar</button>
    </form>

    <?php if (isset($_SESSION['usuario_id'])): ?>
        <hr>
        <p>Actualmente logueado como ID: <?php echo $_SESSION['usuario_id']; ?></p>
        <a href="logout.php">Cerrar Sesión</a>
    <?php endif; ?>

</body>
</html>