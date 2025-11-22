 
<?php
session_start();
require_once __DIR__ . '/../models/Producto.php';
$id_producto = $_GET['id'] ?? 0;
$productoModel = new Producto();
$producto = $productoModel->obtenerPorId($id_producto);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Producto</title>
    <link rel="stylesheet" href="../styles2.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <main>
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>
        <div class="cajaContenidoPrincipal">
            <?php if (!$producto): ?>
                <h1>Producto no encontrado.</h1>
                <a href="catalog.php">Volver</a>
            <?php else: ?>
                <a href="catalog.php">< Volver al Catálogo</a>
                <div style="background: white; padding: 20px; margin-top: 20px; border: 1px solid #ccc; display: flex; gap: 20px;">
                    <div style="flex: 1;">
                        <?php if($producto['url_imagen_principal']): ?>
                            <img src="<?php echo '..' . $producto['url_imagen_principal']; ?>" style="width: 100%; max-width: 400px; border: 1px solid #eee;">
                        <?php else: ?>
                            <div style="width: 100%; height: 300px; background: #eee; display: flex; align-items: center; justify-content: center;">Sin Imagen</div>
                        <?php endif; ?>
                    </div>
                    <div style="flex: 1;">
                        <h1 style="margin-top: 0;"><?php echo htmlspecialchars($producto['titulo']); ?></h1>
                        <p style="font-size: 24px; color: var(--color-principal); font-weight: bold;">$<?php echo $producto['precio']; ?></p>
                        <p><strong>Estado:</strong> <?php echo $producto['estado']; ?> | <strong>Categoría:</strong> <?php echo $producto['nombreCategoria']; ?></p>
                        <p><strong>Vendedor:</strong> <?php echo $producto['vendedor']; ?></p>
                        <hr>
                        <h3>Descripción:</h3>
                        <p><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
                        <br>
                        
                        <?php if (isset($_SESSION['usuario_id'])): ?>
                            <?php if ($_SESSION['usuario_id'] != $producto['id_usuario']): ?>
                                <a href="chat.php?iniciar=1&prod=<?php echo $producto['id']; ?>&vend=<?php echo $producto['id_usuario']; ?>" 
                                   style="display: inline-block; padding: 15px 30px; background: var(--color-principal); color: white; text-decoration: none; font-weight: bold; border-radius: 4px;">
                                    ✉️ Contactar al Vendedor
                                </a>
                            <?php else: ?>
                                <p><em>(Este es tu producto)</em></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="login.php" style="display: inline-block; padding: 10px 20px; background: var(--color-texto-oscuro); color: white; text-decoration: none;">Inicia sesión para contactar</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>