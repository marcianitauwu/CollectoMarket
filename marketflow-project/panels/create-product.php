<?php
session_start();
if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit(); }
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';

$mensaje = "";
$categoriaModel = new Categoria();
$stmtCategorias = $categoriaModel->obtenerTodas();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $producto = new Producto();
    $producto->titulo = $_POST['titulo'] ?? '';
    $producto->descripcion = $_POST['descripcion'] ?? '';
    $producto->precio = $_POST['precio'] ?? 0;
    $producto->estado = $_POST['estado'] ?? 'Nuevo';
    $producto->id_categoria = $_POST['id_categoria'] ?? '';
    $producto->id_usuario = $_SESSION['usuario_id'];

    $url_imagen = '';
    // OJO CON LA RUTA DE SUBIDA: debe ser absoluta o relativa desde este archivo
    $uploadDir = __DIR__ . '/../public/uploads/'; 
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $fileName = uniqid() . '_' . basename($_FILES['imagen']['name']);
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadDir . $fileName)) {
            // Guardamos la ruta relativa para la web
            $url_imagen = '/public/uploads/' . $fileName;
        }
    }
    $producto->url_imagen_principal = $url_imagen;

    if (empty($producto->titulo) || empty($producto->precio)) {
        $mensaje = "Título y Precio son obligatorios.";
    } else {
        if ($producto->crear()) {
            $mensaje = "✅ Producto publicado correctamente.";
        } else {
            $mensaje = "❌ Error al publicar el producto.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Producto</title>
    <link rel="stylesheet" href="../styles2.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <main>
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>
        <div class="cajaContenidoPrincipal">
            <h1>Publicar Nuevo Producto</h1>
            <?php if ($mensaje): ?><p><strong><?php echo $mensaje; ?></strong></p><?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data" style="background: white; padding: 20px; border: 1px solid #ccc; max-width: 600px;">
                <div style="margin-bottom: 10px;"><label style="display:block;">Título:</label><input type="text" name="titulo" required style="width: 100%;"></div>
                <div style="margin-bottom: 10px;"><label style="display:block;">Descripción:</label><textarea name="descripcion" style="width: 100%;" rows="4"></textarea></div>
                <div style="margin-bottom: 10px;"><label style="display:block;">Precio ($):</label><input type="number" step="0.01" name="precio" required style="width: 100%;"></div>
                <div style="margin-bottom: 10px;"><label style="display:block;">Estado:</label>
                    <select name="estado" style="width: 100%;"><option value="Nuevo">Nuevo</option><option value="Usado">Usado</option></select>
                </div>
                <div style="margin-bottom: 10px;"><label style="display:block;">Categoría:</label>
                    <select name="id_categoria" required style="width: 100%;">
                        <?php while ($cat = $stmtCategorias->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['nombreCategoria']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div style="margin-bottom: 10px;"><label style="display:block;">Imagen Principal:</label><input type="file" name="imagen"></div>
                <button type="submit" style="padding: 10px 20px; background: var(--color-secundario); color: white; border: none; cursor: pointer;">Publicar</button>
            </form>
        </div>
    </main>
</body>
</html>