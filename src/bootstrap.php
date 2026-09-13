<?php

// Autoloader simple: mapea el namespace App\ a la carpeta /src
spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return; // no es una clase nuestra, que la maneje otro autoloader
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Config local (credenciales de BD). Si no existe, avisa cómo crearlo.
$localConfig = __DIR__ . '/config/local.php';
if (!file_exists($localConfig)) {
    die(
        'Falta el archivo de configuración local. ' .
        'Copia src/config/local.example.php como src/config/local.php y ajusta tus credenciales.'
    );
}
require $localConfig;

// Sesión — necesaria para login (HU02) y mensajes flash (ej. tras registrarse)
session_start();

// Mientras estamos en desarrollo, mostrar errores ayuda a depurar.
// TODO: desactivar display_errors antes de cualquier entrega/demo pública.
ini_set('display_errors', '1');
error_reporting(E_ALL);
