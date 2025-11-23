<?php
require_once 'config/db.php';
require_once 'includes/header.php';

// Si el usuario ya está logueado, redirigir a la página principal
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Inicializar el mensaje 
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); 
}
?>

<div class="form-container">
    <h2>Inicio de Sesión </h2>
    <p>Como un usuario registrado, quiero iniciar sesión con mis credenciales para acceder a mi perfil y gestionar mis anuncios.</p>

    <?php if ($message): ?>
        <p class="message" style="color: <?php echo strpos($message, 'exitosa') !== false ? 'green' : 'red'; ?>; font-weight: bold;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form action="actions/login_action.php" method="POST">
        
        <div class="form-group">
            <label for="username">Nombre de Usuario:</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" class="btn">Iniciar Sesión</button>
        
        <p style="margin-top: 15px;">
            ¿No tienes cuenta? <a href="register.php">Regístrate aquí.</a>
        </p>
    </form>
</div>

<?php
require_once 'includes/footer.php';
?>
