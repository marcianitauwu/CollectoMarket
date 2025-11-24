<?php
// Inicia la sesión si no está activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si la variable de sesión 'user_id' está establecida
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
 ?> 