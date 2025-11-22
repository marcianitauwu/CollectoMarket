<?php
session_start();
require_once __DIR__ . '/../models/Producto.php';
$productoModel = new Producto();
$stmtProductos = $productoModel->obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo</title>
    <link rel="stylesheet" href="../styles2.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    
    <main>
        
            
            <?php include __DIR__ . '/../includes/sidebar.php'; ?>
        

        <div class="cajaContenidoPrincipal">
            <h1>Catálogo de Productos</h1>
            
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <p>Bienvenido, <?php echo $_SESSION['usuario_nombre']; ?>. <a href="create-product.php" style="color: var(--color-principal);">[+ Publicar Nuevo Producto]</a></p>
            <?php else: ?>
                <p><a href="login.php">Inicia Sesión</a> para comprar o vender.</p>
            <?php endif; ?>
            <hr>
            <h2>Listado Disponible</h2>
            <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse; background: white;">
                <thead style="background: var(--color-fondo-claro);">
                    <tr>
                        <th>Imagen</th>
                        <th>Título</th>
                        <th>Precio</th>
                        <th>Categoría</th>
                        <th>Vendedor</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($prod = $stmtProductos->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td>
                            <?php if($prod['url_imagen_principal']): ?>
                                <img src="<?php echo '..' . $prod['url_imagen_principal']; ?>" width="60" height="60" style="object-fit: cover;">
                            <?php else: ?>
                                Sin imagen
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($prod['titulo']); ?></td>
                        <td style="color: var(--color-principal); font-weight: bold;">$<?php echo $prod['precio']; ?></td>
                        <td><?php echo $prod['nombreCategoria']; ?></td>
                        <td><?php echo $prod['vendedor']; ?></td>
                        <td><a href="product-detail.php?id=<?php echo $prod['id']; ?>" style="color: var(--color-principal);">Ver Detalles</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>