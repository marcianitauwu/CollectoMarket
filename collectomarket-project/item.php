<?php
session_start();
if (!isset($_SESSION['nombre']) || !isset($_SESSION['correo']) || !isset($_SESSION['password']) ) { 
    header("Location: login.php"); exit(); 
}

require_once 'config/db.php';
require_once 'includes/header.php';

// Validar que se haya pasado un ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de artículo no válido.");
}

$item_id = intval($_GET['id']);

// Consulta para obtener el ítem y el nombre del vendedor
$sql = "SELECT i.*, u.username AS seller_username 
        FROM items i 
        JOIN users u ON i.user_id = u.id 
        WHERE i.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Artículo no encontrado.");
}

$item = $result->fetch_assoc();
$stmt->close();
?>

<div class="item-details">
    <a href="index.php" style="display: block; margin-bottom: 20px;">&larr; Volver al Catálogo</a>
    
    <h2><?php echo htmlspecialchars($item['name']); ?></h2>
    
    <div style="display: flex; gap: 30px; margin-top: 20px;">
        <div class="item-image" style="flex: 1;">
            <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="width: 100%; max-height: 400px; object-fit: contain; border: 1px solid #ccc; padding: 10px;">
        </div>
        
        <div class="item-info" style="flex: 1;">
            <h3>Información General</h3>
            <p>Descripción: <?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
            <p>Precio Estimado Real: <strong>$<?php echo number_format($item['price_real'], 2); ?></strong></p>
            <hr>
            
            <h3>Detalles Específicos (<?php echo htmlspecialchars(ucfirst($item['type'])); ?>)</h3>
            <?php if ($item['type'] === 'brainrot'): ?>
                <p>Rareza: <?php echo htmlspecialchars($item['br_rarity']); ?></p>
                <p>Color: <?php echo htmlspecialchars($item['br_color']); ?></p>
                <p>Produce en el Juego: $<?php echo number_format($item['br_profit_game'], 2); ?></p>
                <p>Precio Dentro del Juego: $<?php echo number_format($item['br_price_game'], 2); ?></p>
            <?php elseif ($item['type'] === 'pokemon'): ?>
                <p>Tipo de Energía: <?php echo htmlspecialchars($item['pk_energy_type']); ?></p>
                <p>Rareza: <?php echo htmlspecialchars($item['pk_rarity']); ?></p>
                <p>Puntos de Vida (HP): <?php echo htmlspecialchars($item['pk_hp']); ?></p>
                <p>Poder de Ataque: <?php echo htmlspecialchars($item['pk_attack']); ?></p>
                <p>Edición: <?php echo htmlspecialchars($item['pk_edition']); ?></p>
            <?php endif; ?>

            <hr>
            
            <h3>Datos del Vendedor</h3>
            <p>Vendido por: <strong><?php echo htmlspecialchars($item['seller_username']); ?></strong></p>
            
            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $item['user_id']): ?>
                <a href="chat.php?receiver_id=<?php echo $item['user_id']; ?>&item_id=<?php echo $item['id']; ?>" class="btn" style="margin-top: 15px;">
                    Contactar al Vendedor 
                </a>
            <?php elseif (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $item['user_id']): ?>
                <p style="color: green; font-weight: bold; margin-top: 15px;">(Este es tu propio anuncio)</p>
            <?php else: ?>
                 <p style="color: orange; font-weight: bold; margin-top: 15px;">Inicia sesión para contactar al vendedor.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>
