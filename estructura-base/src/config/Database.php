<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    // No se instancia directamente — se usa Database::getConnection()
    private function __construct() {}

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false, // fuerza sentencias preparadas reales
                ]);
            } catch (PDOException $e) {
                // En desarrollo mostramos el error; en producción esto debería loguearse, no imprimirse.
                die('Error de conexión a la base de datos: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
