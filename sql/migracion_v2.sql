-- ============================================
-- ExpressOrder - Migración v2
-- Agrega: codigo_retiro, unidad_medida,
--         updated_at, atendido_por, notas
-- Correr sobre el schema 'expressorder' que ya
-- tiene las 6 tablas y los datos semilla.
-- ============================================

USE expressorder;

-- --------------------------------------------
-- PRODUCTOS: unidad de medida
-- --------------------------------------------
ALTER TABLE productos
    ADD COLUMN unidad_medida ENUM('unidad', 'lb', 'kg') NOT NULL DEFAULT 'unidad' AFTER stock;

-- --------------------------------------------
-- PEDIDOS: código de retiro, staff que atendió,
-- notas y fecha de última actualización
-- --------------------------------------------
ALTER TABLE pedidos
    ADD COLUMN codigo_retiro VARCHAR(10) UNIQUE AFTER id,
    ADD COLUMN atendido_por INT NULL AFTER usuario_id,
    ADD COLUMN notas TEXT AFTER total,
    ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at,
    ADD FOREIGN KEY (atendido_por) REFERENCES usuarios(id);

-- --------------------------------------------
-- Actualizar productos existentes (los que ya
-- se venden por peso, según el seed original)
-- --------------------------------------------
UPDATE productos SET unidad_medida = 'lb' WHERE nombre LIKE '%Pechuga%';
UPDATE productos SET unidad_medida = 'lb' WHERE nombre LIKE '%Arroz%';

-- --------------------------------------------
-- Generar codigo_retiro para pedidos que ya
-- existan (si insertaste alguno de prueba)
-- --------------------------------------------
UPDATE pedidos
SET codigo_retiro = CONCAT('EO-', LPAD(id, 4, '0'))
WHERE codigo_retiro IS NULL;
