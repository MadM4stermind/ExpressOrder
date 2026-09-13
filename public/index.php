<?php

require __DIR__ . '/../src/bootstrap.php';

use App\Core\Router;
use App\Config\Database;
use App\Controllers\AuthController;

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

// Rutas de autenticación (controlador con stubs, listas para implementar HU01/HU02)
$router->get('/registrarse', [AuthController::class, 'mostrarFormularioRegistro']);
$router->post('/registrarse', [AuthController::class, 'registrar']);
$router->get('/iniciar-sesion', [AuthController::class, 'mostrarFormularioLogin']);
$router->post('/iniciar-sesion', [AuthController::class, 'login']);

// --- Despacho ---
// Quitamos el prefijo de carpeta (ej. /expressorder/public) para que las rutas
// de arriba funcionen igual sin importar dónde esté clonado el proyecto en htdocs.
$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
$uri = $_SERVER['REQUEST_URI'];

if ($scriptDir !== '' && str_starts_with($uri, $scriptDir)) {
    $uri = substr($uri, strlen($scriptDir));
}

$router->dispatch($_SERVER['REQUEST_METHOD'], $uri === '' ? '/' : $uri);
