<?php
session_start();
require_once '../config/db.php';
require_once '../includes/auth.php'; // Solo usuarios logueados pueden enviar mensajes

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $sender_id = $_SESSION['user_id'];
   $receiver_id = intval($_POST['receiver_id'] ?? 0);
   $item_id = intval($_POST['item_id'] ?? 0);
   $message = trim($_POST['message'] ?? '');

   // --- 1. VALIDACIÓN ---
   if (empty($message) || $receiver_id <= 0 || $item_id <= 0) {
      $_SESSION['message'] = "Error: Mensaje o parámetros de chat incompletos.";
      // Redirigir al chat fallido
      header("Location: ../chat.php?receiver_id=" . $receiver_id . "&item_id=" . $item_id);
      exit;
   }

   // --- 2. INSERCIÓN DEL MENSAJE (Criterio: Envío de mensajes) ---
   $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, item_id, message) VALUES (?, ?, ?, ?)");
   $stmt->bind_param("iiis", $sender_id, $receiver_id, $item_id, $message);

   if ($stmt->execute()) {
      // Redirigir de vuelta a la misma ventana de chat para ver el mensaje enviado
      header("Location: ../chat.php?receiver_id=" . $receiver_id . "&item_id=" . $item_id);
      exit;
   } else {
      $_SESSION['message'] = "Error al enviar el mensaje: " . $conn->error;
      header("Location: ../chat.php?receiver_id=" . $receiver_id . "&item_id=" . $item_id);
      exit;
   }

   $stmt->close();
   $conn->close();
} else {
   // Si se accede sin POST, redirigir al catálogo
   header("Location: ../index.php");
   exit;
}
// NOTA: Se omite la etiqueta de cierre ?> para evitar errores de salida.