# ExpressOrder — Diccionario de datos

Basado en `schema.sql` (v2). Todas las tablas usan motor `InnoDB`.

---

## usuarios

| Campo | Tipo | Restricciones | Descripción |
|---|---|---|---|
| id | INT | PK, AUTO_INCREMENT | Identificador único del usuario |
| nombre | VARCHAR(100) | NOT NULL | Nombre completo |
| email | VARCHAR(150) | NOT NULL, UNIQUE | Correo, usado como identificador de login |
| password_hash | VARCHAR(255) | NOT NULL | Hash bcrypt de la contraseña (`password_hash()` de PHP) |
| telefono | VARCHAR(20) | — | Teléfono de contacto, opcional |
| rol | ENUM('cliente','staff','admin') | NOT NULL, DEFAULT 'cliente' | Determina permisos; nunca se asigna desde el cliente en el registro público |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Fecha de creación de la cuenta |

---

## categorias

| Campo | Tipo | Restricciones | Descripción |
|---|---|---|---|
| id | INT | PK, AUTO_INCREMENT | Identificador único de la categoría |
| nombre | VARCHAR(80) | NOT NULL, UNIQUE | Nombre de la categoría (ej. "Lácteos") |

---

## productos

| Campo | Tipo | Restricciones | Descripción |
|---|---|---|---|
| id | INT | PK, AUTO_INCREMENT | Identificador único del producto |
| categoria_id | INT | FK → categorias(id), NOT NULL | Categoría a la que pertenece |
| nombre | VARCHAR(150) | NOT NULL | Nombre del producto |
| descripcion | TEXT | — | Descripción opcional |
| precio | DECIMAL(10,2) | NOT NULL | Precio actual de venta |
| stock | INT | NOT NULL, DEFAULT 0 | Cantidad disponible; se descuenta al confirmar un pedido |
| unidad_medida | ENUM('unidad','lb','kg') | NOT NULL, DEFAULT 'unidad' | Unidad en que se vende y se descuenta el stock |
| imagen_url | VARCHAR(255) | — | Ruta o URL de la imagen del producto |
| activo | BOOLEAN | NOT NULL, DEFAULT TRUE | Si es `FALSE`, se oculta del catálogo sin borrar su historial |

**Índice:** `idx_productos_categoria` sobre `categoria_id`.

---

## horarios_pickup

| Campo | Tipo | Restricciones | Descripción |
|---|---|---|---|
| id | INT | PK, AUTO_INCREMENT | Identificador único del horario |
| fecha | DATE | NOT NULL | Fecha del slot de retiro |
| hora_inicio | TIME | NOT NULL | Inicio de la ventana de retiro |
| hora_fin | TIME | NOT NULL | Fin de la ventana de retiro |
| capacidad_max | INT | NOT NULL, DEFAULT 10 | Máximo de pedidos permitidos en este horario |

---

## pedidos

| Campo | Tipo | Restricciones | Descripción |
|---|---|---|---|
| id | INT | PK, AUTO_INCREMENT | Identificador único del pedido |
| codigo_retiro | VARCHAR(10) | UNIQUE | Código que el cliente presenta al recoger (ej. `EO-0001`) |
| usuario_id | INT | FK → usuarios(id), NOT NULL | Cliente que realizó el pedido |
| atendido_por | INT | FK → usuarios(id), NULL | Staff/admin que gestionó el pedido; NULL hasta que alguien lo atienda |
| horario_id | INT | FK → horarios_pickup(id), NOT NULL | Horario de retiro elegido |
| estado | ENUM('pendiente','confirmado','listo','entregado','cancelado') | NOT NULL, DEFAULT 'pendiente' | Estado actual dentro del flujo de preparación |
| total | DECIMAL(10,2) | NOT NULL, DEFAULT 0 | Suma de los subtotales de `detalle_pedido` |
| notas | TEXT | — | Notas libres del cliente sobre el pedido |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Fecha de creación del pedido |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP | Última vez que cambió el estado u otro campo |

**Índices:** `idx_pedidos_usuario` sobre `usuario_id`, `idx_pedidos_estado` sobre `estado`.

---

## detalle_pedido

| Campo | Tipo | Restricciones | Descripción |
|---|---|---|---|
| id | INT | PK, AUTO_INCREMENT | Identificador único de la línea de detalle |
| pedido_id | INT | FK → pedidos(id), NOT NULL, ON DELETE CASCADE | Pedido al que pertenece esta línea |
| producto_id | INT | FK → productos(id), NOT NULL | Producto incluido en el pedido |
| cantidad | INT | NOT NULL | Cantidad pedida (en la `unidad_medida` del producto) |
| precio_unitario | DECIMAL(10,2) | NOT NULL | Precio del producto **al momento de la compra** — no cambia si el precio del producto cambia después |

**Índice:** `idx_detalle_pedido` sobre `pedido_id`.

---

## Notas de diseño

- **`usuarios` unificada por rol:** clientes, staff y admin comparten tabla; la autorización se valida en cada endpoint según `rol`, no solo en el login.
- **`precio_unitario` congelado en `detalle_pedido`:** decisión de normalización para que el historial de pedidos no se altere si el precio del producto cambia después.
- **Sin tablas de pago ni facturación fiscal (NCF):** fuera de alcance por decisión documentada — el pago se asume en efectivo/tarjeta física al momento del pickup.
- **Concurrencia en `stock` y `capacidad_max`:** ambas se validan y descuentan dentro de la misma transacción al crear el pedido (ver diagrama de actividades del checkout), para evitar condiciones de carrera con pedidos simultáneos.
