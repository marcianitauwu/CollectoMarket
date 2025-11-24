<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // 1. Validación de campos obligatorios
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $_SESSION['message'] = "Todos los campos son obligatorios.";
        header("Location: ../register.php");
        exit;
    }

    // 2. Validación de coincidencia y complejidad (Criterio: Credenciales no válidas)
    if ($password !== $confirm_password) {
        $_SESSION['message'] = "Las contraseñas no coinciden.";
        header("Location: ../register.php");
        exit;
    }
    if (strlen($password) < 8) {
        $_SESSION['message'] = "La contraseña debe tener al menos 8 caracteres.";
        header("Location: ../register.php");
        exit;
    }
    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        $_SESSION['message'] = "El nombre de usuario solo puede contener letras, números y guiones bajos, y debe tener entre 3 y 20 caracteres.";
        header("Location: ../register.php");
        exit;
    }

    // 3. Verificar si el nombre de usuario ya existe
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['message'] = "El nombre de usuario ya está en uso.";
        $stmt->close();
        header("Location: ../register.php");
        exit;
    }
    $stmt->close();

    // 4. Registro Exitoso: Hashear e insertar (Criterio: Registro exitoso)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['message'] = "¡Cuenta creada exitosamente! Ahora puedes iniciar sesión.";
        header("Location: ../login.php");
        exit;
    } else {
        $_SESSION['message'] = "Error al crear la cuenta. Por favor, inténtalo de nuevo más tarde.";
        header("Location: ../register.php");
        exit;
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../register.php");
    exit;
}
// NOTA: Se omite la etiqueta de cierre ?> para evitar errores de salida.