<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catálogo — ExpressOrder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-md-3">
            <h6 class="text-muted text-uppercase small mb-2">Categorías</h6>
            <div class="list-group mb-4">
                <a href="<?= BASE_PATH ?>/catalogo"
                   class="list-group-item list-group-item-action <?= $categoriaId === null ? 'active' : '' ?>">
                    Todas
                </a>
                <?php foreach ($categorias as $cat): ?>
                    <a href="<?= BASE_PATH ?>/catalogo?categoria=<?= (int) $cat['id'] ?>"
                       class="list-group-item list-group-item-action <?= $categoriaId === (int) $cat['id'] ? 'active' : '' ?>">
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-md-9">
            <h4 class="mb-3">Catálogo</h4>

            <?php if (empty($productos)): ?>
                <p class="text-muted">No hay productos disponibles en esta categoría.</p>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php foreach ($productos as $producto):
                        $esPorPeso = in_array($producto['unidad_medida'], ['lb', 'kg'], true);
                        $step = $esPorPeso ? '0.25' : '1';
                        $min = $esPorPeso ? '0.25' : '1';
                        $valorInicial = $esPorPeso ? '0.5' : '1';
                        $inputId = 'cantidad-' . (int) $producto['id'];
                    ?>
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= htmlspecialchars($producto['nombre']) ?></h5>
                                    <p class="card-text text-muted small mb-1">
                                        <?= htmlspecialchars($producto['categoria_nombre']) ?>
                                    </p>
                                    <p class="card-text flex-grow-1">
                                        <?= htmlspecialchars($producto['descripcion'] ?? '') ?>
                                    </p>
                                    <p class="fw-bold mb-2">
                                        RD$ <?= number_format((float) $producto['precio'], 2) ?>
                                        / <?= htmlspecialchars($producto['unidad_medida']) ?>
                                    </p>

                                    <div class="input-group input-group-sm">
                                        <input type="number"
                                               id="<?= $inputId ?>"
                                               class="form-control"
                                               value="<?= $valorInicial ?>"
                                               min="<?= $min ?>"
                                               step="<?= $step ?>"
                                               aria-label="Cantidad en <?= htmlspecialchars($producto['unidad_medida']) ?>">
                                        <button type="button"
                                                class="btn btn-primary btn-agregar-carrito"
                                                data-id="<?= (int) $producto['id'] ?>"
                                                data-nombre="<?= htmlspecialchars($producto['nombre']) ?>"
                                                data-precio="<?= (float) $producto['precio'] ?>"
                                                data-unidad="<?= htmlspecialchars($producto['unidad_medida']) ?>"
                                                data-cantidad-input="<?= $inputId ?>">
                                            Agregar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="<?= BASE_PATH ?>/js/carrito.js"></script>
</body>
</html>