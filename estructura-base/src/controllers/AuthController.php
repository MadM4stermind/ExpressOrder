<?php

namespace App\Controllers;

use App\Config\Database;

class AuthController
{
    // GET /registrarse — TODO (HU01): mostrar el formulario de registro
    public function mostrarFormularioRegistro(): void
    {
        echo 'Formulario de registro — pendiente de implementar (HU01).';
    }

    // POST /registrarse — TODO (HU01): validar datos, hashear contraseña, insertar en usuarios
    public function registrar(): void
    {
        echo 'Procesar registro — pendiente de implementar (HU01).';
    }

    // GET /iniciar-sesion — TODO (HU02): mostrar el formulario de login
    public function mostrarFormularioLogin(): void
    {
        echo 'Formulario de login — pendiente de implementar (HU02).';
    }

    // POST /iniciar-sesion — TODO (HU02): validar credenciales, iniciar sesión
    public function login(): void
    {
        echo 'Procesar login — pendiente de implementar (HU02).';
    }
}
