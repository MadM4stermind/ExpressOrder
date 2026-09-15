<?php

namespace App\Controllers;

class CarritoController
{
    // GET /carrito
    // El contenido se renderiza vía JS (carrito.js) porque el carrito vive en
    // localStorage, no en el servidor — este método solo entrega el HTML base.
    public function index(): void
    {
        require __DIR__ . '/../views/carrito/index.php';
    }
}
