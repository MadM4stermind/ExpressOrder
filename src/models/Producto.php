<?php

namespace App\Models;

use App\Config\Database;

class Producto
{
    // HU03: solo productos activos, con filtro opcional por categoría.
    public static function listarActivos(?int $categoriaId = null): array
    {
        $pdo = Database::getConnection();

        $sql = 'SELECT p.*, c.nombre AS categoria_nombre
                FROM productos p
                JOIN categorias c ON c.id = p.categoria_id
                WHERE p.activo = 1';

        $params = [];
        if ($categoriaId !== null) {
            $sql .= ' AND p.categoria_id = :categoria_id';
            $params['categoria_id'] = $categoriaId;
        }

        $sql .= ' ORDER BY c.nombre, p.nombre';

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }
}
