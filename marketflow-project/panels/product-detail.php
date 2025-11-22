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

                <div class="contenedor-detalle-producto">

                    <div class="detalle-imagen">
                        <?php if($producto['url_imagen_principal']): ?>
                            <img src="<?php echo '..' . $producto['url_imagen_principal']; ?>" class="img-detalle-producto">
                        <?php else: ?>
                            <div class="sin-imagen-detalle">Sin Imagen</div>
                        <?php endif; ?>
                    </div>

                    <div class="detalle-info">
                        <h1><?php echo htmlspecialchars($producto['titulo']); ?></h1>

                        <p class="precio-detalle">$<?php echo $producto['precio']; ?></p>

                        <p>
                            Estado: <?php echo $producto['estado']; ?> |
                            Categoría: <?php echo $producto['nombreCategoria']; ?>
                        </p>

                        <p>Vendedor: <?php echo $producto['vendedor']; ?></p>

                        <hr>

                        <h3>Descripción:</h3>
                        <p><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>

                        <br>

                        <?php if (isset($_SESSION['usuario_id'])): ?>
                            <?php if ($_SESSION['usuario_id'] != $producto['id_usuario']): ?>
                                <a href="chat.php?iniciar=1&prod=<?php echo $producto['id']; ?>&vend=<?php echo $producto['id_usuario']; ?>">
                                    ✉️ Contactar al Vendedor
                                </a>
                            <?php else: ?>
                                <p><em>(Este es tu producto)</em></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="login.php" class="btn-iniciar-sesion-contactar">
                                Inicia sesión para contactar
                            </a>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
