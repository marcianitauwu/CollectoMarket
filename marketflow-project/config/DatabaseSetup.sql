 
-- ================================================
-- Script de Base de Datos para MarketFlow (P2P)
-- ================================================
-- Este script creará la base de datos y todas las tablas necesarias
-- en el orden correcto para respetar las claves foráneas.
-- Se usa el motor InnoDB para soporte de transacciones y FKs,
-- y el conjunto de caracteres utf8mb4 para soporte completo (incluyendo emojis).

-- ------------------------------------------------
-- 1. Crear y Usar la Base de Datos
-- ------------------------------------------------
-- Crea la base de datos si no existe.
CREATE DATABASE IF NOT EXISTS marketflow_db 


-- Selecciona la base de datos para ejecutar los siguientes comandos en ella.
USE marketflow_db;


-- ------------------------------------------------
-- 2. Tabla de Usuarios (Independiente)
-- ------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombreUsuario VARCHAR(50) NOT NULL UNIQUE,
    correoElectronico VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL, -- ¡IMPORTANTE! Guardar siempre el hash (ej. bcrypt), no texto plano.
    rol ENUM('usuario', 'admin') DEFAULT 'usuario',
    estadoCuenta ENUM('activa', 'suspendida') DEFAULT 'activa',
    avatar_url VARCHAR(255) NULL, -- URL a la imagen de perfil del usuario.
    fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- ------------------------------------------------
-- 3. Tabla de Categorías (Independiente)
-- ------------------------------------------------
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombreCategoria VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT NULL
) ENGINE=InnoDB;


-- ------------------------------------------------
-- 4. Tabla de Productos (Depende de Usuarios y Categorías)
-- ------------------------------------------------
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    estado ENUM('Nuevo', 'Usado') NOT NULL,
    disponible TINYINT(1) DEFAULT 1, -- 1 significa Disponible, 0 significa Vendido/Retirado.
    url_imagen_principal VARCHAR(255) NULL, -- URL de la única imagen principal del producto.
    fechaPublicacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    -- Claves Foráneas (FKs)
    id_usuario INT NOT NULL, -- El vendedor que publicó el producto.
    id_categoria INT NOT NULL, -- La categoría a la que pertenece.
    
    -- Restricciones de Clave Foránea
    CONSTRAINT fk_producto_usuario 
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id) 
        ON DELETE CASCADE, -- Si se borra el usuario, se borran sus productos.

    CONSTRAINT fk_producto_categoria 
        FOREIGN KEY (id_categoria) REFERENCES categorias(id) 
        ON DELETE RESTRICT -- No permite borrar una categoría si tiene productos asociados.
) ENGINE=InnoDB;


-- ------------------------------------------------
-- 5. Tabla de Chats (Depende de Productos y Usuarios)
-- ------------------------------------------------
-- Representa una "sala" de conversación sobre un producto específico entre dos usuarios.
CREATE TABLE IF NOT EXISTS chats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL, -- El producto sobre el que están hablando.
    id_comprador INT NOT NULL, -- El usuario interesado (quien inicia el chat).
    id_vendedor INT NOT NULL, -- El dueño del producto.
    fechaCreacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    -- Restricciones de Clave Foránea
    CONSTRAINT fk_chat_producto 
        FOREIGN KEY (id_producto) REFERENCES productos(id) 
        ON DELETE CASCADE, -- Si se borra el producto, se borra el chat asociado.

    CONSTRAINT fk_chat_comprador 
        FOREIGN KEY (id_comprador) REFERENCES usuarios(id) 
        ON DELETE CASCADE,

    CONSTRAINT fk_chat_vendedor 
        FOREIGN KEY (id_vendedor) REFERENCES usuarios(id) 
        ON DELETE CASCADE
) ENGINE=InnoDB;


-- ------------------------------------------------
-- 6. Tabla de Mensajes (Depende de Chats y Usuarios)
-- ------------------------------------------------
-- Los mensajes individuales dentro de una sala de chat.
CREATE TABLE IF NOT EXISTS mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_chat INT NOT NULL, -- A qué conversación pertenece este mensaje.
    id_remitente INT NOT NULL, -- Quién envió este mensaje específico.
    contenido TEXT NOT NULL,
    leido TINYINT(1) DEFAULT 0, -- 0 = No leído, 1 = Leído.
    fechaEnvio DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    -- Restricciones de Clave Foránea
    CONSTRAINT fk_mensaje_chat 
        FOREIGN KEY (id_chat) REFERENCES chats(id) 
        ON DELETE CASCADE, -- Si se borra el chat, se borran sus mensajes.

    CONSTRAINT fk_mensaje_remitente 
        FOREIGN KEY (id_remitente) REFERENCES usuarios(id) 
        ON DELETE CASCADE
) ENGINE=InnoDB;


-- ------------------------------------------------
-- 7. Datos Iniciales (Opcional pero recomendado)
-- ------------------------------------------------
-- Insertamos algunas categorías básicas para poder empezar a usar el sistema.
-- Usamos INSERT IGNORE para evitar errores si el script se corre varias veces.
INSERT IGNORE INTO categorias (nombreCategoria, descripcion) VALUES
('Electrónica', 'Móviles, ordenadores, cámaras y accesorios.'),
('Hogar y Muebles', 'Decoración, muebles, electrodomésticos y jardín.'),
('Moda y Accesorios', 'Ropa, calzado, bolsos y joyería para todos.'),
('Deportes y Ocio', 'Equipamiento deportivo, bicicletas, camping e instrumentos.'),
('Vehículos', 'Coches, motos, recambios y accesorios.'),
('Otros', 'Todo lo que no encaja en las demás categorías.');

-- ================================================
-- Fin del Script
-- ================================================