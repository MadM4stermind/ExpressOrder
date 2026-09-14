<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrarse — ExpressOrder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container" style="max-width: 480px; margin-top: 60px;">
    <h1 class="h4 mb-4">Crear cuenta</h1>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_PATH ?>/registrarse">
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control"
                   value="<?= htmlspecialchars($old['nombre'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                   value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Teléfono (opcional)</label>
            <input type="text" name="telefono" class="form-control"
                   value="<?= htmlspecialchars($old['telefono'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" minlength="6" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Crear cuenta</button>
    </form>

    <p class="mt-3 text-center">
        <a href="<?= BASE_PATH ?>/iniciar-sesion">¿Ya tienes cuenta? Inicia sesión</a>
    </p>
</div>
</body>
</html>
