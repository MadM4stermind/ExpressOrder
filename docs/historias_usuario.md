# ExpressOrder — Historias de usuario

Formato: **Como** [rol] **quiero** [acción] **para** [objetivo].
Cada historia incluye criterios de aceptación y el sprint donde se planificó (ver plan de sprints).

---

## Épica 1 — Cuenta y sesión (Sprint 1)

### HU01 — Registrarse
Como **cliente**, quiero crear una cuenta para poder hacer pedidos.

**Criterios de aceptación**
- El formulario pide nombre, email, teléfono (opcional) y contraseña.
- El email debe ser único (constraint `UNIQUE` en `usuarios.email`); si ya existe, se muestra error claro.
- La contraseña se guarda con `password_hash()` (bcrypt), nunca en texto plano.
- El rol se asigna automáticamente como `cliente` — el formulario no permite elegirlo.

### HU02 — Iniciar sesión
Como **usuario registrado** (cliente, staff o admin), quiero iniciar sesión para acceder a las funciones según mi rol.

**Criterios de aceptación**
- Login por email + contraseña.
- Si las credenciales son incorrectas, se muestra un mensaje genérico (no revela si el email existe o no).
- Al iniciar sesión, el sistema redirige según el `rol` (cliente → catálogo, staff/admin → panel).

---

## Épica 2 — Catálogo y carrito (Sprint 1 y 2)

### HU03 — Explorar catálogo por categoría *(Sprint 1)*
Como **visitante o cliente**, quiero ver los productos organizados por categoría para encontrar lo que busco sin necesidad de iniciar sesión.

**Criterios de aceptación**
- Accesible sin login.
- Filtro por categoría (`categorias` ↔ `productos`).
- Solo se muestran productos con `activo = TRUE`.

### HU04 — Agregar producto al carrito *(Sprint 2)*
Como **cliente**, quiero agregar productos al carrito para armar mi pedido antes de confirmarlo.

**Criterios de aceptación**
- Respeta la `unidad_medida` del producto (unidad, lb, kg) al pedir cantidad.
- El carrito vive en el navegador (no se pierde si el cliente tiene que iniciar sesión a mitad del proceso).
- Se puede seguir agregando productos las veces que el cliente quiera antes de proceder.

### HU05 — Ajustar o quitar producto del carrito *(Sprint 2)*
Como **cliente**, quiero modificar cantidades o quitar productos del carrito antes de confirmar.

**Criterios de aceptación**
- Cambiar cantidad recalcula el subtotal en pantalla.
- Quitar un producto lo elimina completamente del carrito.

### HU06 — Aviso de carrito vacío *(Sprint 2)*
Como **cliente**, quiero recibir un aviso claro si intento continuar sin productos en el carrito.

**Criterios de aceptación**
- Al presionar "Proceder a realizar pedido" con el carrito vacío, se muestra el aviso y no se avanza al checkout.

---

## Épica 3 — Checkout (Sprint 2)

### HU07 — Seleccionar horario de retiro
Como **cliente**, quiero elegir un horario disponible para saber cuándo puedo pasar a recoger mi pedido.

**Criterios de aceptación**
- Solo se listan horarios con cupos disponibles (`capacidad_max` no alcanzada).
- Si el horario se llena justo antes de confirmar, se le pide elegir otro (ver HU08).

### HU08 — Confirmar pedido
Como **cliente**, quiero confirmar mi pedido para que la tienda empiece a prepararlo.

**Criterios de aceptación**
- Antes de crear el pedido, el sistema valida en una sola transacción: stock suficiente de cada producto **y** cupo disponible en el horario elegido.
- Si algo falla la validación, se le indica qué ajustar (producto agotado u horario lleno) sin perder el resto del carrito.
- Al confirmar exitosamente: se crea el registro en `pedidos`, se genera `codigo_retiro`, se descuenta `stock` de cada producto y se descuenta el cupo del horario — todo en la misma transacción.

### HU09 — Ver confirmación con código de retiro
Como **cliente**, quiero ver mi código de retiro después de confirmar para poder identificarme al recoger el pedido.

**Criterios de aceptación**
- Se muestra el `codigo_retiro`, el horario elegido y el resumen del pedido en pantalla.

---

## Épica 4 — Historial y cancelación (Sprint 4)

### HU10 — Ver historial de pedidos
Como **cliente**, quiero ver mis pedidos anteriores y su estado actual.

**Criterios de aceptación**
- Requiere sesión iniciada.
- Lista pedidos ordenados por fecha, mostrando `estado` y `codigo_retiro` de cada uno.

### HU11 — Cancelar pedido
Como **cliente**, quiero poder cancelar un pedido que aún no ha sido entregado.

**Criterios de aceptación**
- Solo disponible si `estado` es `pendiente` o `confirmado` (no si ya está `listo` o `entregado`).
- Al cancelar, se debe restaurar el stock descontado y el cupo del horario liberado.

---

## Épica 5 — Gestión de pedidos, Staff (Sprint 3 y 4)

### HU12 — Ver pedidos entrantes *(Sprint 3)*
Como **staff**, quiero ver la lista de pedidos pendientes para saber qué preparar.

**Criterios de aceptación**
- Requiere sesión con rol `staff` o `admin`.
- Lista ordenable por horario de retiro y estado.

### HU13 — Cambiar estado del pedido *(Sprint 4)*
Como **staff**, quiero actualizar el estado de un pedido para reflejar su progreso.

**Criterios de aceptación**
- Transiciones válidas: `pendiente → confirmado → listo → entregado`.
- Cada cambio actualiza `updated_at` y registra `atendido_por` con el id del staff que hizo el cambio.

### HU14 — Verificar código de retiro *(Sprint 3)*
Como **staff**, quiero validar el código de retiro del cliente antes de marcar el pedido como entregado.

**Criterios de aceptación**
- El staff introduce el `codigo_retiro`; el sistema lo compara contra el pedido que intenta marcar como `entregado`.
- Si no coincide, no permite el cambio de estado a `entregado`.

---

## Épica 6 — Administración (Sprint 3 y 4)

### HU15 — Gestionar productos y categorías *(Sprint 3)*
Como **admin**, quiero crear, editar y desactivar productos y categorías para mantener el catálogo actualizado.

**Criterios de aceptación**
- CRUD completo sobre `productos` y `categorias`.
- Desactivar un producto (`activo = FALSE`) lo oculta del catálogo sin borrar su historial en pedidos ya realizados.

### HU16 — Gestionar horarios y capacidad de pickup *(Sprint 4)*
Como **admin**, quiero crear y ajustar los horarios de retiro disponibles y su capacidad máxima.

**Criterios de aceptación**
- CRUD sobre `horarios_pickup`.
- No se puede reducir `capacidad_max` por debajo de la cantidad de pedidos ya confirmados para ese horario.

### HU17 — Gestionar usuarios *(Sprint 4)*
Como **admin**, quiero cambiar el rol de un usuario cuando sea necesario.

**Criterios de aceptación**
- Solo un admin puede modificar el campo `rol` de otro usuario.
- El cambio queda registrado (campo `updated_by` o log simple de auditoría).
