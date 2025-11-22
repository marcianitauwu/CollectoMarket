<?php
require_once __DIR__ . '/../models/Usuario.php';
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : "";
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : "";
    $password = isset($_POST['contrasena']) ? $_POST['contrasena'] : "";

    if (empty($nombre) || empty($correo) || empty($password)) {
        $mensaje = "Por favor, rellena todos los campos.";
    } elseif (strlen($password) < 6) {
        $mensaje = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $usuario = new Usuario();
        $resultado = $usuario->registrarse($nombre, $correo, $password);
        if ($resultado === true) {
            $mensaje = "✅ Cuenta creada correctamente. <a href='login.php'>Inicia sesión aquí</a>.";
            $nombre = $correo = "";
        } else {
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
    <link rel="stylesheet" href="../styles2.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <main>
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>
        <div class="cajaContenidoPrincipal">

            <div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; background: white;">
                <h2>Crear Cuenta</h2>
                <?php if ($mensaje): ?><p><strong><?php echo $mensaje; ?></strong></p><?php endif; ?>

                <form action="" method="POST">
                    <div style="margin-bottom: 10px;">
                        <label for="nombre" style="display:block;">Nombre de Usuario:</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre ?? ''); ?>" required style="width: 100%;">
                    </div>
                    <div style="margin-bottom: 10px;">
                        <label for="correo" style="display:block;">Correo Electrónico:</label>
                        <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($correo ?? ''); ?>" required style="width: 100%;">
                    </div>
                    <div style="margin-bottom: 10px;">
                        <label for="contrasena" style="display:block;">Contraseña (mín. 6 caracteres):</label>
                        <input type="password" id="contrasena" name="contrasena" required style="width: 100%;">
                    </div>
                    <button type="submit" style="width: 100%; padding: 10px; background: #3498DB; color: white; border: none;">Registrarse</button>
                </form>
                <br>
                <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>
            </div>

        </div>
    </main>
</body>
</html>