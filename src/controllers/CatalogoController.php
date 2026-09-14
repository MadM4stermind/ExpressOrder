<?php

namespace App\Controllers;

use App\Models\Producto;
use App\Models\Categoria;

class CatalogoController
{
    // GET /catalogo  y  GET /catalogo?categoria={id}
    public function index(): void
    {
        $categorias = Categoria::listarTodas();

        $categoriaId = isset($_GET['categoria']) ? (int) $_GET['categoria'] : null;

        // Si mandan algo que no sea un id positivo válido, lo tratamos como "sin filtro"
        // en vez de dejar que rompa la consulta.
        if ($categoriaId !== null && $categoriaId <= 0) {
            $categoriaId = null;
        }

        $productos = Producto::listarActivos($categoriaId);

        require __DIR__ . '/../views/catalogo/index.php';
    }
}
