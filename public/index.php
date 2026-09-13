<?php

require __DIR__ . '/../src/bootstrap.php';

use App\Core\Router;
use App\Config\Database;
use App\Controllers\AuthController;

// BASE_PATH = prefijo de carpeta donde vive el proyecto dentro de htdocs
// (ej. "/expressorder/public"). La usan los controladores para redirigir
// y las vistas para armar los <form action="..."> y los links.
$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
define('BASE_PATH', $scriptDir);

$router = new Router();

// Ruta de prueba — confirma que el router funciona
$router->get('/', function () {
    echo 'ExpressOrder — estructura base funcionando 🚀';
});

// Ruta de prueba — confirma que la conexión PDO a MySQL funciona
$router->get('/test-db', function () {
    Database::getConnection();
    echo '✅ Conexión a la base de datos exitosa.';
});

// Rutas de autenticación
$router->get('/registrarse', [AuthController::class, 'mostrarFormularioRegistro']);
$router->post('/registrarse', [AuthController::class, 'registrar']);
$router->get('/iniciar-sesion', [AuthController::class, 'mostrarFormularioLogin']);
$router->post('/iniciar-sesion', [AuthController::class, 'login']);
$router->get('/cerrar-sesion', [AuthController::class, 'logout']);

// Destino post-login para staff/admin (RF05: valida el rol en el propio endpoint,
// no solo en el login) — placeholder hasta que se implemente el panel real (Sprint 3).
$router->get('/panel', function () {
    $rol = $_SESSION['usuario_rol'] ?? null;

    if ($rol === null) {
        header('Location: ' . BASE_PATH . '/iniciar-sesion');
        exit;
    }

    if (!in_array($rol, ['staff', 'admin'], true)) {
        http_response_code(403);
        echo 'No tienes permiso para ver esta página.';
        return;
    }

    echo 'Panel de ' . htmlspecialchars($rol) . ' — pendiente de implementar (Sprint 3).';
});

// --- Despacho ---
$uri = $_SERVER['REQUEST_URI'];

if (BASE_PATH !== '' && str_starts_with($uri, BASE_PATH)) {
    $uri = substr($uri, strlen(BASE_PATH));
}

$router->dispatch($_SERVER['REQUEST_METHOD'], $uri === '' ? '/' : $uri);
