 
<?php
// Incluir el modelo
require_once __DIR__ . '/../models/Usuario.php';

$mensaje = "";

// Si el formulario se ha enviado por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y limpiar datos básicos
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : "";
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : "";
    $password = isset($_POST['contrasena']) ? $_POST['contrasena'] : "";

    // Validaciones sencillas
    if (empty($nombre) || empty($correo) || empty($password)) {
        $mensaje = "Por favor, rellena todos los campos.";
    } elseif (strlen($password) < 6) {
        $mensaje = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        // Instanciar el usuario
        $usuario = new Usuario();

        // Intentar registrar al usuario
        $resultado = $usuario->registrarse($nombre, $correo, $password);

        if ($resultado === true) {
            // Éxito
            $mensaje = "✅ Cuenta creada correctamente. ID: " . $usuario->id . ". Puedes <a href='login.php'>iniciar sesión</a>.";
            // Limpiar campos para que no aparezcan en el formulario de nuevo
            $nombre = $correo = "";
        } else {
            // Error (el método registrarse devuelve un mensaje de error en caso de fallo)
            $mensaje = "❌ " . $resultado;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Sencillo</title>
</head>
<body>

    <h2>Crear Cuenta</h2>

    <?php if ($mensaje): ?>
        <p><strong><?php echo $mensaje; ?></strong></p>
    <?php endif; ?>

    <form action="" method="POST">
        <div>
            <label for="nombre">Nombre de Usuario:</label><br>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre ?? ''); ?>" required>
        </div>
        <br>
        <div>
            <label for="correo">Correo Electrónico:</label><br>
            <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($correo ?? ''); ?>" required>
        </div>
        <br>
        <div>
            <label for="contrasena">Contraseña (mín. 6 caracteres):</label><br>
            <input type="password" id="contrasena" name="contrasena" required>
        </div>
        <br>
        <button type="submit">Registrarse</button>
    </form>

    <br>
    <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>

</body>
</html>