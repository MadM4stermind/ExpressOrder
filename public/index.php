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

// --- Despacho ---
$uri = $_SERVER['REQUEST_URI'];

if (BASE_PATH !== '' && str_starts_with($uri, BASE_PATH)) {
    $uri = substr($uri, strlen(BASE_PATH));
}

$router->dispatch($_SERVER['REQUEST_METHOD'], $uri === '' ? '/' : $uri);
