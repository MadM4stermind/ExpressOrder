# ExpressOrder — Requerimientos funcionales y no funcionales

## Requerimientos funcionales (RF)

### Cuenta y sesión
- **RF01** — El sistema debe permitir a un cliente registrarse con nombre, email y contraseña.
- **RF02** — El sistema debe validar que el email de registro sea único.
- **RF03** — El sistema debe permitir iniciar sesión mediante email y contraseña.
- **RF04** — El sistema debe asignar el rol `cliente` por defecto a todo registro público, sin permitir que el usuario elija su propio rol.
- **RF05** — El sistema debe restringir el acceso a las funciones de staff y admin según el campo `rol` del usuario autenticado, validado en cada endpoint.

### Catálogo
- **RF06** — El sistema debe mostrar el catálogo de productos organizado por categoría, accesible sin necesidad de sesión iniciada.
- **RF07** — El sistema debe ocultar del catálogo los productos marcados como inactivos (`activo = FALSE`).
- **RF08** — El sistema debe mostrar la cantidad de cada producto en su unidad de medida correspondiente (unidad, libra o kilogramo).

### Carrito
- **RF09** — El sistema debe permitir agregar productos al carrito, ajustar cantidades y eliminar productos antes de confirmar el pedido.
- **RF10** — El sistema debe conservar el contenido del carrito aunque el cliente tenga que iniciar sesión a mitad del proceso de compra.
- **RF11** — El sistema debe impedir proceder al checkout si el carrito está vacío, mostrando un aviso al cliente.

### Checkout y pedidos
- **RF12** — El sistema debe permitir al cliente seleccionar un horario de retiro entre los que tengan cupo disponible.
- **RF13** — El sistema debe validar, en una misma transacción, que exista stock suficiente de cada producto del carrito y cupo disponible en el horario elegido antes de crear el pedido.
- **RF14** — El sistema debe generar un código de retiro único al confirmar un pedido.
- **RF15** — El sistema debe descontar el stock de los productos y el cupo del horario únicamente cuando el pedido se confirma exitosamente.
- **RF16** — El sistema debe registrar el precio de cada producto al momento de la compra, independiente de si el precio cambia después.
- **RF17** — El sistema debe permitir al cliente consultar su historial de pedidos con su estado actual.
- **RF18** — El sistema debe permitir al cliente cancelar un pedido únicamente si su estado es `pendiente` o `confirmado`.
- **RF19** — Al cancelar un pedido, el sistema debe restaurar el stock de los productos y el cupo del horario correspondiente.

### Gestión de pedidos (Staff)
- **RF20** — El sistema debe permitir al staff visualizar los pedidos entrantes ordenados por horario de retiro.
- **RF21** — El sistema debe permitir al staff actualizar el estado de un pedido siguiendo la secuencia `pendiente → confirmado → listo → entregado`.
- **RF22** — El sistema debe registrar qué miembro del staff gestionó cada cambio de estado de un pedido.
- **RF23** — El sistema debe exigir la verificación del código de retiro antes de permitir marcar un pedido como `entregado`.

### Administración
- **RF24** — El sistema debe permitir al administrador crear, editar y desactivar productos y categorías.
- **RF25** — El sistema debe permitir al administrador crear y editar horarios de retiro, incluyendo su capacidad máxima.
- **RF26** — El sistema debe impedir reducir la capacidad de un horario por debajo de la cantidad de pedidos ya confirmados en él.
- **RF27** — El sistema debe permitir al administrador cambiar el rol de un usuario existente.
- **RF28** — El administrador debe heredar todas las funciones disponibles para el staff, además de las exclusivas de administración.

---

## Requerimientos no funcionales (RNF)

### Seguridad
- **RNF01** — Las contraseñas deben almacenarse utilizando `password_hash()` (bcrypt), nunca en texto plano.
- **RNF02** — Las consultas SQL deben ejecutarse mediante sentencias preparadas (PDO) para prevenir inyección SQL.
- **RNF03** — El sistema debe validar el rol del usuario en el backend en cada solicitud sensible, no solo en la pantalla de login.
- **RNF04** — Los mensajes de error de login deben ser genéricos, sin revelar si el email ingresado existe en el sistema.

### Confiabilidad e integridad de datos
- **RNF05** — La creación de un pedido, el descuento de stock y el descuento de cupo del horario deben ejecutarse como una operación atómica (transacción SQL), evitando pedidos parciales o inconsistentes ante fallos.
- **RNF06** — El sistema debe prevenir condiciones de carrera cuando dos o más clientes intenten reservar el mismo producto o el mismo cupo de horario simultáneamente.

### Usabilidad
- **RNF07** — El carrito debe persistir en el navegador del cliente sin requerir pasos adicionales de su parte al iniciar sesión.
- **RNF08** — La interfaz debe usar Bootstrap para mantener consistencia visual y responsividad básica en dispositivos móviles y de escritorio.

### Rendimiento
- **RNF09** — Las consultas sobre `productos` y `pedidos` deben apoyarse en índices (`categoria_id`, `usuario_id`, `estado`) para mantener tiempos de respuesta aceptables conforme crezca el catálogo o el historial de pedidos.

### Compatibilidad y entorno técnico
- **RNF10** — El sistema debe ejecutarse sobre el stack HTML/CSS/JavaScript, PHP y MySQL/MariaDB, en un entorno XAMPP.
- **RNF11** — El esquema de base de datos debe ser compatible con MariaDB (motor incluido en XAMPP), sin depender de características exclusivas de MySQL 8.

### Mantenibilidad
- **RNF12** — El código fuente debe versionarse con Git, con historial de commits organizado por sprint según la metodología Scrum adoptada.

### Restricciones de alcance (decisiones documentadas)
- **RNF13** — El sistema no debe integrar pasarelas de pago electrónico; el pago se asume en efectivo o tarjeta física al momento del retiro.
- **RNF14** — El sistema no debe generar comprobantes fiscales (NCF); esta funcionalidad queda fuera del alcance del proyecto.
- **RNF15** — El sistema no debe sincronizar el stock en tiempo real con sistemas externos; el stock se gestiona únicamente dentro de la propia base de datos de ExpressOrder.
