-- ============================================
-- ExpressOrder - Esquema de base de datos (v2)
-- Monográfico: Sistema de pre-orden y pickup
-- ============================================

USE expressorder;

-- --------------------------------------------
-- USUARIOS (clientes, staff y admin en una sola tabla)
-- --------------------------------------------
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    rol ENUM('cliente', 'staff', 'admin') NOT NULL DEFAULT 'cliente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- --------------------------------------------
-- CATEGORIAS
-- --------------------------------------------
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- --------------------------------------------
-- PRODUCTOS
-- --------------------------------------------
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    unidad_medida ENUM('unidad', 'lb', 'kg') NOT NULL DEFAULT 'unidad',
    imagen_url VARCHAR(255),
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    INDEX idx_productos_categoria (categoria_id)
) ENGINE=InnoDB;

-- --------------------------------------------
-- HORARIOS_PICKUP
-- --------------------------------------------
CREATE TABLE horarios_pickup (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    capacidad_max INT NOT NULL DEFAULT 10
) ENGINE=InnoDB;

-- --------------------------------------------
-- PEDIDOS
-- --------------------------------------------
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_retiro VARCHAR(10) UNIQUE,
    usuario_id INT NOT NULL,
    atendido_por INT NULL,
    horario_id INT NOT NULL,
    estado ENUM('pendiente', 'confirmado', 'listo', 'entregado', 'cancelado') NOT NULL DEFAULT 'pendiente',
    total DECIMAL(10,2) NOT NULL DEFAULT 0,
    notas TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (atendido_por) REFERENCES usuarios(id),
    FOREIGN KEY (horario_id) REFERENCES horarios_pickup(id),
    INDEX idx_pedidos_usuario (usuario_id),
    INDEX idx_pedidos_estado (estado)
) ENGINE=InnoDB;

-- --------------------------------------------
-- DETALLE_PEDIDO
-- --------------------------------------------
CREATE TABLE detalle_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    INDEX idx_detalle_pedido (pedido_id)
) ENGINE=InnoDB;

-- ============================================
-- DATOS SEMILLA (para pruebas)
-- ============================================

INSERT INTO categorias (nombre) VALUES
    ('Lácteos'), ('Carnes'), ('Abarrotes'), ('Bebidas'), ('Frutas y vegetales');

INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, unidad_medida, activo) VALUES
    (1, 'Leche entera 1L', 'Leche pasteurizada', 95.00, 50, 'unidad', TRUE),
    (2, 'Pechuga de pollo', 'Pollo fresco', 120.00, 30, 'lb', TRUE),
    (3, 'Arroz selecto', 'Arroz blanco', 45.00, 200, 'lb', TRUE),
    (4, 'Refresco 2L', 'Bebida carbonatada', 110.00, 60, 'unidad', TRUE);

INSERT INTO horarios_pickup (fecha, hora_inicio, hora_fin, capacidad_max) VALUES
    (CURDATE() + INTERVAL 1 DAY, '09:00:00', '11:00:00', 15),
    (CURDATE() + INTERVAL 1 DAY, '11:00:00', '13:00:00', 15),
    (CURDATE() + INTERVAL 1 DAY, '15:00:00', '17:00:00', 15);

-- Usuario admin de prueba (cambia el hash antes de usar en producción)
-- password_hash generado con password_hash('admin123', PASSWORD_BCRYPT) en PHP
INSERT INTO usuarios (nombre, email, password_hash, rol) VALUES
    ('Admin ExpressOrder', 'admin@expressorder.test', '$2y$10$examplehashreplacewithrealone', 'admin');
