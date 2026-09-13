<?php

namespace App\Models;

use App\Config\Database;

class Usuario
{
    public static function emailExiste(string $email): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);

        return (bool) $stmt->fetch();
    }

    public static function crear(string $nombre, string $email, ?string $telefono, string $passwordHash): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'INSERT INTO usuarios (nombre, email, password_hash, telefono, rol)
             VALUES (:nombre, :email, :password_hash, :telefono, "cliente")'
        );
        $stmt->execute([
            'nombre'        => $nombre,
            'email'         => $email,
            'password_hash' => $passwordHash,
            'telefono'      => $telefono,
        ]);

        return (int) $pdo->lastInsertId();
    }

    // Usado por HU01 para chequear duplicados; lo va a reusar HU02 para el login.
    public static function buscarPorEmail(string $email): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch();

        return $usuario ?: null;
    }
}
