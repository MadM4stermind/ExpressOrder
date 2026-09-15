<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_PATH ?>/catalogo">ExpressOrder</a>

        <div class="d-flex align-items-center ms-auto">
            <a href="<?= BASE_PATH ?>/carrito" class="text-white me-3 position-relative text-decoration-none">
                🛒
                <span id="carrito-contador"
                      class="badge bg-danger rounded-pill position-absolute d-none"
                      style="top: -8px; left: 16px; font-size: 0.65rem;"></span>
            </a>

            <?php if (!empty($_SESSION['usuario_nombre'])): ?>
                <span class="navbar-text text-white me-3">
                    Hola, <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
                </span>
                <a href="<?= BASE_PATH ?>/cerrar-sesion" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
            <?php else: ?>
                <a href="<?= BASE_PATH ?>/iniciar-sesion" class="btn btn-outline-light btn-sm me-2">Iniciar sesión</a>
                <a href="<?= BASE_PATH ?>/registrarse" class="btn btn-light btn-sm">Registrarse</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
