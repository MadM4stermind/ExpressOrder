<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        // Quita query string (?foo=bar) y la barra final, si la hay
        $path = parse_url($uri, PHP_URL_PATH);
        $path = rtrim($path, '/');
        if ($path === '') {
            $path = '/';
        }

        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            http_response_code(404);
            echo '404 — Ruta no encontrada: ' . htmlspecialchars($method . ' ' . $path);
            return;
        }

        if (is_array($handler)) {
            [$class, $action] = $handler;
            (new $class())->$action();
            return;
        }

        // Handler tipo closure/función
        $handler();
    }
}
