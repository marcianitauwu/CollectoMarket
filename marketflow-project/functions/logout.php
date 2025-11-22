<?php
// functions/logout.php

session_start();
session_unset();
session_destroy();

// --- CORRECCIÓN PARA IR AL CATÁLOGO ---

// "../" = Salir de la carpeta 'functions'
// "panels/" = Entrar a la carpeta 'panels'
// "catalog.php" = El archivo destino
header("Location: ../panels/catalog.php"); 
exit();
?>