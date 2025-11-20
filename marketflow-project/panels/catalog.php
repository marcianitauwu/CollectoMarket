<?php
// panels/catalog.php

session_start();
// ... (Toda tu lógica PHP, includes de modelos, etc.) ...

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarketFlow - Explorar Productos</title>
    <link rel="stylesheet" href="../styles2.css"> <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400&display=swap" rel="stylesheet">
</head>
<body>

    <?php include __DIR__ . '/../includes/header.php'; ?>
    
    <main>
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>
   
    </main>

</body>
</html>