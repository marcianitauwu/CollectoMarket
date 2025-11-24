<?php
session_start();
if (!isset($_SESSION['nombre']) || !isset($_SESSION['correo']) || !isset($_SESSION['password']) ) { 
    header("Location: login.php"); exit(); 
}
require_once 'config/db.php';
require_once 'includes/header.php';

// --- LÓGICA DE BÚSQUEDA Y FILTRADO (HU4, HU5) ---
$sql_where = "WHERE 1=1";
$params = [];
$types = "";

// 1. Búsqueda por Nombre (HU4)
if (!empty($_GET['search'])) {
    $search = "%" . $_GET['search'] . "%";
    $sql_where .= " AND name LIKE ?";
    $params[] = $search;
    $types .= "s";
}

// 2. Filtro por Tipo/Categoría (HU5)
if (!empty($_GET['type']) && in_array($_GET['type'], ['brainrot', 'pokemon'])) {
    $type = $_GET['type'];
    $sql_where .= " AND type = ?";
    $params[] = $type;
    $types .= "s";
}

// Consulta SQL final para el catálogo (HU3)
$sql = "SELECT id, user_id, type, name, price_real, image_path FROM items " . $sql_where . " ORDER BY created_at DESC";

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    // Crear referencias para bind_param
    $bind_params = array_merge([$types], $params);
    $ref_params = [];
    foreach ($bind_params as $key => $value) {
        $ref_params[$key] = &$bind_params[$key];
    }
    call_user_func_array([$stmt, 'bind_param'], $ref_params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Catálogo de Coleccionables </h2>

<form method="GET" action="index.php" class="filter-form">
    <input type="text" name="search" placeholder="Buscar por nombre..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
    
    <select name="type">
        <option value="">Filtrar por Tipo</option>
        <option value="brainrot" <?php echo ($_GET['type'] ?? '') == 'brainrot' ? 'selected' : ''; ?>>Brainrots</option>
        <option value="pokemon" <?php echo ($_GET['type'] ?? '') == 'pokemon' ? 'selected' : ''; ?>>Cartas Pokémon</option>
    </select>
    
    <button type="submit" class="btn">Aplicar</button>
    <a href="index.php" class="btn" style="background-color: gray;">Limpiar Filtros</a>
</form>

<div class="item-grid">
    <?php if ($result->num_rows > 0): ?>
        <?php while($item = $result->fetch_assoc()): ?>
            <div class="item-card">
                <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                <div style="padding: 15px;">
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p>Tipo: <strong><?php echo htmlspecialchars(ucfirst($item['type'])); ?></strong></p>
                    <p>Precio Estimado: <strong>$<?php echo number_format($item['price_real'], 2); ?></strong></p>
                    <a href="item.php?id=<?php echo $item['id']; ?>" class="btn">Ver Detalles (HU6)</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No se encontraron coleccionables que coincidan con los criterios.</p>
    <?php endif; ?>
</div>

<?php
$stmt->close();
require_once 'includes/footer.php';
?>
