<?php
require_once 'includes/auth.php'; // Requiere que el usuario esté logueado
require_once 'config/db.php';
require_once 'includes/header.php';

// Validar parámetros (receptor y artículo)
if (!isset($_GET['receiver_id']) || !isset($_GET['item_id']) || !is_numeric($_GET['receiver_id']) || !is_numeric($_GET['item_id'])) {
    die("Parámetros de chat incompletos.");
}

$receiver_id = intval($_GET['receiver_id']);
$item_id = intval($_GET['item_id']);
$sender_id = $_SESSION['user_id'];

// Asegurarse de que no estás chateando contigo mismo
if ($sender_id === $receiver_id) {
    // Redirigir si el usuario intenta chatear consigo mismo
    header("Location: item.php?id=" . $item_id); 
    exit("No puedes iniciar un chat contigo mismo.");
}

// Obtener información del receptor y del ítem
$stmt_info = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt_info->bind_param("i", $receiver_id);
$stmt_info->execute();
$receiver_info = $stmt_info->get_result()->fetch_assoc();
$stmt_info->close();

$stmt_item = $conn->prepare("SELECT name FROM items WHERE id = ?");
$stmt_item->bind_param("i", $item_id);
$stmt_item->execute();
$item_info = $stmt_item->get_result()->fetch_assoc();
$stmt_item->close();

if (!$receiver_info || !$item_info) {
    die("Vendedor o artículo no encontrado.");
}

$receiver_username = $receiver_info['username'];
$item_name = $item_info['name'];

// --- Lógica para cargar los mensajes (Inicio de chat exitoso - HU8) ---
$sql_messages = "SELECT m.*, u.username AS sender_username 
                 FROM messages m
                 JOIN users u ON m.sender_id = u.id
                 WHERE m.item_id = ? AND 
                       ((m.sender_id = ? AND m.receiver_id = ?) OR 
                        (m.sender_id = ? AND m.receiver_id = ?))
                 ORDER BY sent_at ASC";

$stmt_msg = $conn->prepare($sql_messages);
$stmt_msg->bind_param("iiiii", $item_id, $sender_id, $receiver_id, $receiver_id, $sender_id);
$stmt_msg->execute();
$messages_result = $stmt_msg->get_result();

$message_status = '';
if (isset($_SESSION['message'])) {
    $message_status = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<h2>Chat sobre: <strong><?php echo htmlspecialchars($item_name); ?></strong></h2>
<h3>Hablando con: <strong><?php echo htmlspecialchars($receiver_username); ?></strong></h3>

<?php if ($message_status): ?>
    <p class="message" style="color: red; font-weight: bold;"><?php echo htmlspecialchars($message_status); ?></p>
<?php endif; ?>

<div class="chat-window">
    <?php if ($messages_result->num_rows > 0): ?>
        <?php while($msg = $messages_result->fetch_assoc()): ?>
            <div class="message-row" style="text-align: <?php echo $msg['sender_id'] == $sender_id ? 'right' : 'left'; ?>;">
                <span style="font-size: 0.8em; color: gray; display: block;"><?php echo htmlspecialchars($msg['sender_id'] == $sender_id ? 'Tú' : $msg['sender_username']); ?> - <?php echo date('H:i', strtotime($msg['sent_at'])); ?></span>
                <p><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align: center; color: gray;">Inicia la conversación. ¡Sé amable!</p>
    <?php endif; ?>
</div>

<form action="actions/send_message.php" method="POST">
    <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
    <div style="display: flex; gap: 10px;">
        <textarea name="message" required placeholder="Escribe tu mensaje..." style="flex: 1; padding: 10px; resize: none;"></textarea>
        <button type="submit" class="btn">Enviar</button>
    </div>
</form>

<?php
$stmt_msg->close();
require_once 'includes/footer.php';
?>
