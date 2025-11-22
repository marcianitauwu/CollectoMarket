 
<?php
session_start();
if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit(); }
require_once __DIR__ . '/../models/Chat.php';
require_once __DIR__ . '/../models/Mensaje.php';

$miId = $_SESSION['usuario_id'];
$chatModel = new Chat();
$mensajeModel = new Mensaje();
$chatActualId = $_GET['id'] ?? null;

// Lógica de iniciar/enviar mensaje (igual que antes)
if (isset($_GET['iniciar']) && isset($_GET['prod']) && isset($_GET['vend'])) {
    $nuevoChatId = $chatModel->iniciarConversacion($_GET['prod'], $miId, $_GET['vend']);
    if ($nuevoChatId) { header("Location: chat.php?id=" . $nuevoChatId); exit(); }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mensaje']) && $chatActualId) {
    if (!empty(trim($_POST['mensaje']))) { $mensajeModel->enviar($chatActualId, $miId, $_POST['mensaje']); }
}

$stmtMisChats = $chatModel->obtenerChatsDeUsuario($miId);
$stmtMensajesChat = ($chatActualId) ? $mensajeModel->obtenerMensajesDeChat($chatActualId) : null;
$datosChatActual = ($chatActualId) ? $chatModel->obtenerPorId($chatActualId) : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Mensajes</title>
    <link rel="stylesheet" href="../styles2.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <main>
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>
        <div class="cajaContenidoPrincipal" style="display: flex; height: calc(100vh - 100px);">
            
            <div style="width: 300px; border-right: 1px solid #ccc; background: white; overflow-y: auto;">
                <h3 style="padding: 10px; margin: 0; background: var(--color-fondo-claro);">Mis Conversaciones</h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                <?php while ($chat = $stmtMisChats->fetch(PDO::FETCH_ASSOC)): ?>
                    <li style="border-bottom: 1px solid #eee;">
                        <a href="chat.php?id=<?php echo $chat['id']; ?>" 
                           style="display: block; padding: 15px; text-decoration: none; color: var(--color-texto-oscuro); <?php echo ($chatActualId == $chat['id']) ? 'background: #f0f8ff;' : ''; ?>">
                            <strong>Prod: <?php echo htmlspecialchars($chat['producto_titulo']); ?></strong><br>
                            <small>Con: <?php echo htmlspecialchars($chat['otro_usuario_nombre']); ?></small>
                        </a>
                    </li>
                <?php endwhile; ?>
                </ul>
            </div>

            <div style="flex: 1; display: flex; flex-direction: column; background: #f9f9f9;">
                <?php if ($chatActualId && $stmtMensajesChat): ?>
                    <div style="padding: 15px; background: white; border-bottom: 1px solid #ccc;">
                        Chat #<?php echo $chatActualId; ?> (Producto ID: <?php echo $datosChatActual['id_producto']; ?>)
                    </div>
                    
                    <div style="flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 10px;">
                        <?php while ($msg = $stmtMensajesChat->fetch(PDO::FETCH_ASSOC)): 
                            $esMio = ($msg['id_remitente'] == $miId);
                        ?>
                            <div style="max-width: 70%; padding: 10px 15px; border-radius: 10px; 
                                        align-self: <?php echo $esMio ? 'flex-end' : 'flex-start'; ?>;
                                        background: <?php echo $esMio ? '#dcf8c6' : 'white'; ?>;
                                        border: 1px solid <?php echo $esMio ? '#c3e6cb' : '#ccc'; ?>;">
                                <?php if(!$esMio): ?><strong><?php echo htmlspecialchars($msg['remitente_nombre']); ?>:</strong><br><?php endif; ?>
                                <?php echo htmlspecialchars($msg['contenido']); ?>
                                <div style="text-align: right; font-size: 0.8em; color: grey; margin-top: 5px;"><?php echo date('H:i', strtotime($msg['fechaEnvio'])); ?></div>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <form action="chat.php?id=<?php echo $chatActualId; ?>" method="POST" style="padding: 15px; background: white; border-top: 1px solid #ccc; display: flex; gap: 10px;">
                        <textarea name="mensaje" rows="2" style="flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" required placeholder="Escribe tu mensaje..."></textarea>
                        <button type="submit" style="padding: 0 30px; background: var(--color-principal); color: white; border: none; border-radius: 4px; cursor: pointer;">Enviar ✈️</button>
                    </form>
                <?php else: ?>
                    <div style="flex: 1; display: flex; align-items: center; justify-content: center; color: grey;">
                        << Selecciona una conversación de la izquierda para empezar a chatear.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>