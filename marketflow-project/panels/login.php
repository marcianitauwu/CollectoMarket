<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';

$mensaje = "";
if (isset($_SESSION['usuario_id'])) { header("Location: catalog.php"); exit(); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : "";
    $password = isset($_POST['contrasena']) ? $_POST['contrasena'] : "";

    if (empty($correo) || empty($password)) {
        $mensaje = "Por favor, rellena todos los campos.";
    } else {
        $usuario = new Usuario();
        if ($usuario->iniciarSesion($correo, $password)) {
            $_SESSION['usuario_id'] = $usuario->id;
            $_SESSION['usuario_nombre'] = $usuario->nombreUsuario;
            header("Location: catalog.php");
            exit();
        } else {
            $mensaje = "❌ Credenciales incorrectas.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../styles2.css"> </head>
<body>
    
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <main>
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <div class="cajaContenidoPrincipal">
            
            <div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; background: white;">
                <h2>Iniciar Sesión</h2>
                <?php if ($mensaje): ?><p style="color: red;"><strong><?php echo $mensaje; ?></strong></p><?php endif; ?>

                <form action="" method="POST">
                    <div style="margin-bottom: 10px;">
                        <label for="correo" style="display:block;">Correo Electrónico:</label>
                        <input type="email" id="correo" name="correo" required style="width: 100%;">
                    </div>
                    <div style="margin-bottom: 10px;">
                        <label for="contrasena" style="display:block;">Contraseña:</label>
                        <input type="password" id="contrasena" name="contrasena" required style="width: 100%;">
                    </div>
                    <button type="submit" style="width: 100%; padding: 10px; background: #3498DB; color: white; border: none;">Entrar</button>
                </form>
                <br>
                <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a>.</p>
            </div>
            </div>
    </main>
</body>
</html>