<?php
session_start();
require_once '../config/db.php';
require_once '../includes/auth.php'; // Asegura que solo usuarios logueados accedan

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $user_id = $_SESSION['user_id'];
   $name = trim($_POST['name'] ?? '');
   $description = trim($_POST['description'] ?? '');
   $price_real = floatval($_POST['price_real'] ?? 0);
   $type = $_POST['type'] ?? '';

   // --- 1. VALIDACIÓN DE CAMPOS COMUNES (Criterio: Validación de campos) ---
   if (empty($name) || empty($description) || $price_real <= 0 || empty($type)) {
      $_SESSION['message'] = "Error: Los campos Nombre, Descripción, Precio Real y Tipo son obligatorios.";
      header("Location: ../publish.php");
      exit;
   }
    
   // Validar subida de imagen
   if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
      $_SESSION['message'] = "Error al subir la imagen o la imagen es obligatoria.";
      header("Location: ../publish.php");
      exit;
   }

   // --- 2. MANEJO DE IMAGEN ---
   $target_dir = "../assets/uploads/";
   // Generar nombre de archivo único para evitar colisiones
   $image_name = uniqid("item_", true) . "_" . basename($_FILES["image"]["name"]);
   $target_file = $target_dir . $image_name;
   $image_path = "assets/uploads/" . $image_name; // Ruta a guardar en la DB

   if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
      $_SESSION['message'] = "Error al mover el archivo subido.";
      header("Location: ../publish.php");
      exit;
   }

   // --- 3. INSERCIÓN EN LA BASE DE DATOS (Preparación) ---
   $sql = "";
   $params = [$user_id, $type, $name, $description, $price_real, $image_path];

   if ($type === 'brainrot') {
      $br_rarity = trim($_POST['br_rarity'] ?? NULL);
      $br_color = trim($_POST['br_color'] ?? NULL);
      $br_profit_game = floatval($_POST['br_profit_game'] ?? NULL);
      $br_price_game = floatval($_POST['br_price_game'] ?? NULL);

      $sql = "INSERT INTO items (user_id, type, name, description, price_real, image_path, br_rarity, br_color, br_profit_game, br_price_game) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $params = array_merge($params, [$br_rarity, $br_color, $br_profit_game, $br_price_game]);

   } elseif ($type === 'pokemon') {
      $pk_energy_type = trim($_POST['pk_energy_type'] ?? NULL);
      $pk_rarity = trim($_POST['pk_rarity'] ?? NULL);
      $pk_hp = intval($_POST['pk_hp'] ?? NULL);
      $pk_attack = intval($_POST['pk_attack'] ?? NULL);
      $pk_edition = trim($_POST['pk_edition'] ?? NULL);

      $sql = "INSERT INTO items (user_id, type, name, description, price_real, image_path, pk_energy_type, pk_rarity, pk_hp, pk_attack, pk_edition) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
     $params = array_merge($params, [$pk_energy_type, $pk_rarity, $pk_hp, $pk_attack, $pk_edition]);
   } else {
      $_SESSION['message'] = "Error: Tipo de coleccionable no válido.";
      // Eliminar la imagen subida si falla la validación del tipo
      unlink($target_file);
      header("Location: ../publish.php");
      exit;
   }

   // --- 4. EJECUCIÓN con bind_param dinámico ---
   $stmt = $conn->prepare($sql);
    
   // Generar el string de tipos dinámicamente. Usamos 's' para casi todo y 'i'/'d' para números.
   // Esta parte es compleja, usaremos una solución simple y efectiva:
   $types_string = 'isssds'; // base: user_id, type, name, desc, price_real(d), image_path

   if ($type === 'brainrot') {
      $types_string .= 'ssdd'; // br_rarity, br_color, br_profit_game(d), br_price_game(d)
   } elseif ($type === 'pokemon') {
      $types_string .= 'ssiis'; // pk_energy_type, pk_rarity, pk_hp(i), pk_attack(i), pk_edition
   }
    
   // Crear referencias para bind_param (requerimiento de PHP para arrays dinámicos)
   $bind_params = array_merge([$types_string], $params);
   $ref_params = [];
   foreach ($bind_params as $key => $value) {
      $ref_params[$key] = &$bind_params[$key];
   }
    
   // Ejecutar bind_param
   call_user_func_array([$stmt, 'bind_param'], $ref_params);


   if ($stmt->execute()) {
      // Publicación Exitosa (Criterio: Publicación exitosa)
      $_SESSION['message'] = "¡Anuncio publicado exitosamente!";
      header("Location: ../index.php");
      exit;
   } else {
      $_SESSION['message'] = "Error al crear la publicación: " . $conn->error;
      // Eliminar la imagen subida en caso de fallo DB.
      unlink($target_file);
      header("Location: ../publish.php");
      exit;
   }

   $stmt->close();
   $conn->close();
} else {
   header("Location: ../publish.php");
   exit;
}
?> 