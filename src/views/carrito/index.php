<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tu carrito — ExpressOrder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4 mb-5">
    <h4 class="mb-3">Tu carrito</h4>

    <div id="carrito-vacio" class="text-muted d-none">
        Tu carrito está vacío. <a href="<?= BASE_PATH ?>/catalogo">Explora el catálogo</a> para agregar productos.
    </div>

    <table class="table align-middle bg-white d-none" id="tabla-carrito">
        <thead>
        <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th style="width: 140px;">Cantidad</th>
            <th>Subtotal</th>
            <th></th>
        </tr>
        </thead>
        <tbody id="carrito-filas"></tbody>
        <tfoot>
        <tr>
            <th colspan="3" class="text-end">Total</th>
            <th id="carrito-total">RD$ 0.00</th>
            <th></th>
        </tr>
        </tfoot>
    </table>

    <a href="<?= BASE_PATH ?>/catalogo" class="btn btn-outline-secondary btn-sm mt-2">
        ← Seguir explorando el catálogo
    </a>
</div>

<script src="<?= BASE_PATH ?>/js/carrito.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        ExpressOrderCarrito.renderizarVistaCarrito();
    });
</script>
</body>
</html>
