# ExpressOrder

Sistema de pre-orden y pickup en tienda (curbside/in-store pickup), desarrollado como monográfico de grado — Ingeniería en Ciencias de la Computación, UASD.

## Descripción

ExpressOrder permite a un cliente explorar el catálogo de un supermercado, armar un pedido, elegir un horario de retiro, y recogerlo en tienda con un código de retiro — sin filas ni esperas en caja para artículos ya pagados/reservados.

## Stack

- **Frontend:** HTML, CSS, JavaScript, Bootstrap
- **Backend:** PHP (PDO, sentencias preparadas)
- **Base de datos:** MySQL / MariaDB (vía XAMPP)
- **Metodología:** Scrum

## Estructura del repositorio

```
├── public/     → HTML, CSS, JS servido por Apache
├── src/        → Lógica PHP (controladores, modelos, conexión a BD)
├── sql/        → Scripts de creación y migración de la base de datos
└── docs/       → Documentación del monográfico (casos de uso, ERD, requerimientos, etc.)
```

## Documentación

| Archivo | Contenido |
|---|---|
| `docs/casos_de_uso.puml` | Diagrama de casos de uso (PlantUML) |
| `docs/actividad_checkout.puml` | Diagrama de actividades del flujo de checkout |
| `docs/historias_usuario.md` | Backlog de historias de usuario por épica y sprint |
| `docs/diccionario_de_datos.md` | Diccionario de datos del esquema |
| `docs/requerimientos.md` | Requerimientos funcionales y no funcionales |
| `sql/schema.sql` | Script completo del esquema de base de datos |
| `sql/migracion_v2.sql` | Migración incremental (código de retiro, unidad de medida, etc.) |

## Alcance (decisiones documentadas)

- Sin pasarela de pago electrónico — se asume pago en efectivo/tarjeta física al retiro.
- Sin facturación fiscal (NCF).
- Sin sincronización de inventario en tiempo real con sistemas externos.

Ver `docs/requerimientos.md` (sección RNF13-15) para el detalle.

## Instalación local (XAMPP)

1. Clonar este repo dentro de `htdocs`.
2. Crear el schema `expressorder` en MySQL Workbench o phpMyAdmin.
3. Ejecutar `sql/schema.sql` para crear las tablas y datos de prueba.
4. Acceder vía `http://localhost/expressorder/public`.
