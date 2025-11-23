<?php
// Inicia la sesión para gestionar el login/registro
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CollectoMart - Plataforma de Coleccionables</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="header">
    <h1><a href="index.php">COLLECTOMART</a></h1>
    <nav class="nav">
        <a href="index.php">Catálogo (Explorar)</a>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="publish.php" class="btn">Publicar Anuncio</a>
            <a href="profile.php">Mi Perfil</a>
            <a href="logout.php">Cerrar Sesión</a>
        <?php else: ?>
            <a href="login.php">Iniciar Sesión</a>
            <a href="register.php">Registrarse</a>
        <?php endif; ?>
    </nav>
</header>

<main class="container">