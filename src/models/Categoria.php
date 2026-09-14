<?php

namespace App\Models;

use App\Config\Database;

class Categoria
{
    public static function listarTodas(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT * FROM categorias ORDER BY nombre');

        return $stmt->fetchAll();
    }
}
