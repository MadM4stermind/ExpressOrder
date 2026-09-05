# ExpressOrder — Especificación de casos de uso

## Actores

| Actor | Descripción |
|---|---|
| **Cliente** | Usuario que navega el catálogo y realiza pre-pedidos |
| **Staff** | Personal de tienda que gestiona pedidos e inventario |
| **Admin** | Hereda todo de Staff (generalización) + gestión de productos/usuarios |

**Generalización:** `Admin ──▷ Staff` (triángulo vacío, apunta de Admin hacia Staff)

---

## Casos de uso

### Cliente
- **UC1** — Registrarse
- **UC2** — Iniciar sesión
- **UC3** — Ver catálogo
- **UC4** — Realizar pedido
- **UC5** — Ver estado del pedido
- **UC6** — Cancelar pedido

### Staff (heredados por Admin)
- **UC7** — Ver pedidos entrantes
- **UC8** — Confirmar pedido
- **UC9** — Marcar pedido listo
- **UC10** — Verificar código de retiro
- **UC11** — Entregar pedido
- **UC12** — Gestionar inventario (stock)

### Admin (exclusivos)
- **UC13** — Gestionar productos y categorías
- **UC14** — Gestionar usuarios

---

## Asociaciones (actor — línea simple, sin flecha)

| Actor | Casos de uso conectados |
|---|---|
| Cliente | UC1, UC2, UC3, UC4, UC5, UC6 |
| Staff | UC2, UC7, UC8, UC9, UC10, UC11, UC12 |
| Admin | UC2, UC13, UC14 *(el resto los hereda de Staff, no se dibujan de nuevo)* |

---

## Relaciones `<<include>>` (línea punteada, flecha abierta, obligatorio)

| Caso base | Incluye a |
|---|---|
| UC4 Realizar pedido | UC2 Iniciar sesión |
| UC4 Realizar pedido | UC3 Ver catálogo |
| UC5 Ver estado del pedido | UC2 Iniciar sesión |
| UC6 Cancelar pedido | UC2 Iniciar sesión |
| UC7 Ver pedidos entrantes | UC2 Iniciar sesión |
| UC11 Entregar pedido | UC10 Verificar código de retiro |
| UC12 Gestionar inventario | UC2 Iniciar sesión |
| UC13 Gestionar productos y categorías | UC2 Iniciar sesión |
| UC14 Gestionar usuarios | UC2 Iniciar sesión |

---

## Relaciones `<<extend>>` (línea punteada, flecha abierta, opcional)

| Caso que extiende | Extiende a | Condición / punto de extensión |
|---|---|---|
| UC6 Cancelar pedido | UC5 Ver estado del pedido | Solo si el pedido aún no fue entregado |
| UC8 Confirmar pedido | UC7 Ver pedidos entrantes | Staff decide confirmar el pedido visto |
| UC9 Marcar pedido listo | UC7 Ver pedidos entrantes | Staff decide marcarlo listo |
| UC11 Entregar pedido | UC7 Ver pedidos entrantes | Staff decide entregarlo |

---

## Notas de armado

- No dibujes flechas de Admin hacia UC7–UC12 — esas ya le llegan por la flecha de generalización hacia Staff.
- UC1 (Registrarse) no incluye login — es una vía de entrada alternativa, no depende de sesión iniciada.
- UC8, UC9, UC11 podrían combinarse en un solo caso "Actualizar estado del pedido" si prefieres un diagrama más simple — pero mantenerlos separados se ve mejor documentado en la defensa porque refleja el flujo real (`pendiente → confirmado → listo → entregado`).
