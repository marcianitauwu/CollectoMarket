<?php
session_start();
// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Incluir el modelo
require_once __DIR__ . '/../models/Producto.php';

$productoModel = new Producto();
// Obtener productos solo del usuario logueado
//stmt es la sentencia
$stmtMisProductos = $productoModel->obtenerPorUsuario($_SESSION['usuario_id']);
$cantidadProductos = $stmtMisProductos->rowCount();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Publicaciones</title>
    <link rel="stylesheet" href="../styles2.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    
    <main>
        
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>
        
        <div class="cajaContenidoPrincipal">
            <div class="header-flex">
                <h1>Mis Publicaciones</h1>
                <a href="create-product.php" class="btn-crear">Nueva Publicación</a>
            </div>
            
            <hr>

            <?php if ($cantidadProductos == 0): ?>
                <div>
                    <p style="font-size: 1.2em; color: var(--color-texto-secundario);">Aún no has publicado nada.</p>
                    <p>¡Es hora de vender! <a href="create-product.php" style="color: var(--color-principal);">Publica tu primer producto aquí</a>.</p>
                </div>
            <?php else: ?>
                <div class="grid-productos">
                    <?php while ($prod = $stmtMisProductos->fetch(PDO::FETCH_ASSOC)): 
                        // Preparar ruta de imagen o usar placeholder si no existe
                        if (!empty($prod['url_imagen_principal'])) {
                            // Si la URL NO está vacía, concatenamos los puntos para subir de carpeta
                            $rutaImagen = '..' . $prod['url_imagen_principal'];
                        } else {
                            // Si la URL SÍ está vacía (o es null/cero), usamos la imagen de relleno
                            $rutaImagen = '../images/imagenProductoPorDefecto.png';                         
                        }
                    ?>
                    
                        <div class="tarjeta-producto">
                            <div class="imagen-container">
                                <img src="<?php echo $rutaImagen; ?>" alt="<?php echo htmlspecialchars($prod['titulo']); ?>">
                                <span class="etiqueta-estado <?php echo $prod['disponible'] ? 'dispo' : 'vendido'; ?>">
                                    <?php echo $prod['disponible'] ? 'Activo' : 'Vendido'; ?>
                                </span>
                            </div>
                            
                            <div class="contenido-tarjeta">
                                <h3 class="titulo" title="<?php echo htmlspecialchars($prod['titulo']); ?>">
                                    <?php echo htmlspecialchars($prod['titulo']); ?>
                                </h3>
                                
                                <p class="precio">$<?php echo number_format($prod['precio'], 2); ?></p>
                                
                                <div class="info-extra">
                                    <span>📅 <?php echo date('d/m/Y', strtotime($prod['fechaPublicacion'])); ?></span>
                                    <span>📦 <?php echo htmlspecialchars($prod['estado']); // Nuevo/Usado ?></span>
                                </div>

                                <a href="product-detail.php?id=<?php echo $prod['id']; ?>" class="btn-ver-detalles">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                        <?php endwhile; ?>
                </div>
            <?php endif; ?>
            
        </div>
                    </main>
</body>
</html>