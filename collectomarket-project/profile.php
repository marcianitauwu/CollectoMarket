<?php
require_once 'includes/auth.php';
require_once 'config/db.php';
require_once 'includes/header.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// 1. Lógica para obtener los ANUNCIOS PROPIOS (Vendedor)
$sql_items = "SELECT id, name, price_real FROM items WHERE user_id = ? ORDER BY created_at DESC";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $user_id);
$stmt_items->execute();
$my_items = $stmt_items->get_result();
$stmt_items->close();

// 2. Lógica para obtener CHATS RECIBIDOS (Vendedor)
// Consulta para obtener conversaciones únicas donde el usuario actual es el RECEPTOR
$sql_chats = "
    SELECT 
        m.sender_id, 
        m.item_id, 
        u.username AS buyer_username,
        i.name AS item_name
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    JOIN items i ON m.item_id = i.id
    WHERE m.receiver_id = ?
    GROUP BY m.sender_id, m.item_id
    ORDER BY MAX(m.sent_at) DESC
";
$stmt_chats = $conn->prepare($sql_chats);
$stmt_chats->bind_param("i", $user_id);
$stmt_chats->execute();
$my_chats = $stmt_chats->get_result();
$stmt_chats->close();

?>

<h2>Perfil de Usuario: <?php echo htmlspecialchars($username); ?></h2>

<p>Esta es tu sección de perfil. Puedes ver los anuncios que has publicado y gestionar tus mensajes.</p>

<h3>Mensajes Recibidos (Compradores)</h3>

<?php if ($my_chats->num_rows > 0): ?>
    <div style="background-color: var(--bg-card); padding: 20px; border-radius: var(--border-radius); box-shadow: var(--shadow-subtle); margin-bottom: 30px;">
        <ul style="list-style: none; padding: 0;">
            <?php while($chat = $my_chats->fetch_assoc()): 
                // Para abrir el chat, el vendedor (usuario actual) pasa el ID del comprador (sender_id) como su receptor
                $chat_link = "chat.php?receiver_id=" . $chat['sender_id'] . "&item_id=" . $chat['item_id'];
            ?>
                <li style="border-bottom: 1px dashed #e9ecef; padding: 10px 0;">
                    <a href="<?php echo $chat_link; ?>" style="display: flex; justify-content: space-between; align-items: center; color: var(--text-color); font-weight: 600;">
                        <span>
                            Conversación con <strong><?php echo htmlspecialchars($chat['buyer_username']); ?></strong>
                        </span>
                        <span style="font-size: 0.9em; color: var(--text-light);">
                            (Artículo: <?php echo htmlspecialchars($chat['item_name']); ?>)
                        </span>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
<?php else: ?>
    <p style="margin-bottom: 30px;">Aún no has recibido mensajes de interesados en tus artículos.</p>
<?php endif; ?>

<h3>Mis Anuncios Publicados (<?php echo $my_items->num_rows; ?>)</h3>
<?php if ($my_items->num_rows > 0): ?>
    <div style="background-color: var(--bg-card); padding: 20px; border-radius: var(--border-radius); box-shadow: var(--shadow-subtle);">
        <ul style="list-style: none; padding: 0;">
            <?php while($item = $my_items->fetch_assoc()): ?>
                <li style="border-bottom: 1px dashed #e9ecef; padding: 10px 0;">
                    <a href="item.php?id=<?php echo $item['id']; ?>" style="color: var(--secondary-color);">
                        <strong><?php echo htmlspecialchars($item['name']); ?></strong> ($<?php echo number_format($item['price_real'], 2); ?>)
                    </a>
                    </li>
            <?php endwhile; ?>
        </ul>
    </div>
<?php else: ?>
    <p>Aún no has publicado ningún anuncio. <a href="publish.php">¡Publica uno ahora!</a></p>
<?php endif; ?>

<?php
require_once 'includes/footer.php';
?>