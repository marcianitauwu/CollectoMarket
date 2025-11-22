 
<?php
session_start();
if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit(); }
require_once __DIR__ . '/../models/Producto.php';
$productoModel = new Producto();
$stmtMisProductos = $productoModel->obtenerPorUsuario($_SESSION['usuario_id']);
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
            <h1>Mis Publicaciones</h1>
            <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse; background: white;">
                <thead style="background: var(--color-fondo-claro);">
                    <tr>
                        <th>Título</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($prod = $stmtMisProductos->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($prod['titulo']); ?></td>
                        <td>$<?php echo $prod['precio']; ?></td>
                        <td><?php echo $prod['disponible'] ? '🟢 Disponible' : '🔴 Vendido'; ?></td>
                        <td><?php echo $prod['fechaPublicacion']; ?></td>
                        <td><a href="product-detail.php?id=<?php echo $prod['id']; ?>" style="color: var(--color-principal);">Ver</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php if ($stmtMisProductos->rowCount() == 0): ?>
                <p style="margin-top: 20px;">Aún no has publicado nada. <a href="create-product.php">¡Publica tu primer producto!</a></p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>