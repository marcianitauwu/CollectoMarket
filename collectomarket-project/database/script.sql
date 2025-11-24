-- ==========================================================
-- SCRIPT DE CREACIÓN DE BASE DE DATOS COLLECTOMART_DB
-- ==========================================================

-- 1. Creación de la base de datos
CREATE DATABASE IF NOT EXISTS collectomart_db;
USE collectomart_db;

-- 2. Tabla de Usuarios (Cubre HU1: Registro y HU2: Login)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,       -- Nombre único para login
    password VARCHAR(255) NOT NULL,             -- Contraseña encriptada
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Tabla de Ítems/Anuncios (Cubre HU3, HU5, HU6, HU7)
-- Usa campos NULLABLE para características específicas de Brainrots o Pokémon
CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,                       -- Quién lo vende
    type ENUM('brainrot', 'pokemon') NOT NULL,  -- Tipo de coleccionable
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price_real DECIMAL(10, 2) NOT NULL,         -- Precio estimado en dinero real
    image_path VARCHAR(255) NOT NULL,           -- Ruta de la imagen subida
    
    -- Campos específicos de BRAINROTS
    br_rarity VARCHAR(50) NULL,
    br_color VARCHAR(30) NULL,
    br_profit_game DECIMAL(15, 2) NULL,         -- Dinero que produce en el juego
    br_price_game DECIMAL(15, 2) NULL,          -- Precio dentro del juego

    -- Campos específicos de POKEMON
    pk_energy_type VARCHAR(30) NULL,
    pk_rarity VARCHAR(50) NULL,
    pk_hp INT NULL,                             -- Puntos de vida (HP)
    pk_attack INT NULL,                         -- Poder de ataque
    pk_edition VARCHAR(50) NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Relación con la tabla users
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 4. Tabla de Mensajes (Cubre HU8: Contactar a un vendedor)
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    item_id INT NULL,                          -- Referencia al artículo del que hablan (opcional)
    message TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Relaciones con la tabla users e items
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id),
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE SET NULL
);