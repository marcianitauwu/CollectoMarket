<?php
require_once 'config/db.php';
require_once 'includes/header.php';

// Inicializar el mensaje de error o éxito
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); 
}
?>

<div class="form-container">
    <h2>Registro de Cuenta </h2>
    <p>Como un nuevo usuario, quiero registrarme en la aplicación con un nombre y una contraseña para poder crear anuncios de venta y contactar a otros vendedores.</p>

    <?php if ($message): ?>
        <p class="message" style="color: red; font-weight: bold;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form action="actions/register_action.php" method="POST">
        
        <div class="form-group">
            <label for="username">Nombre de Usuario (Único):</label>
            <input type="text" id="username" name="username" required 
                   placeholder="Ej: coleccionista_pro7">
        </div>
        
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required
                   placeholder="Mínimo 8 caracteres">
            <small>La contraseña debe ser segura.</small>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirmar Contraseña:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <button type="submit" class="btn">Registrarse</button>
        
        <p style="margin-top: 15px;">
            ¿Ya tienes cuenta? <a href="login.php">Inicia Sesión aquí.</a>
        </p>
    </form>
</div>

<?php
require_once 'includes/footer.php';
?>