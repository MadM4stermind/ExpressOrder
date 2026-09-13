<?php

namespace App\Controllers;

use App\Models\Usuario;

class AuthController
{
    // GET /registrarse
    public function mostrarFormularioRegistro(array $errores = [], array $old = []): void
    {
        require __DIR__ . '/../views/auth/registro.php';
    }

    // POST /registrarse — HU01
    public function registrar(): void
    {
        $nombre   = trim($_POST['nombre'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $password = $_POST['password'] ?? '';

        $errores = [];

        if ($nombre === '') {
            $errores[] = 'El nombre es obligatorio.';
        }

        $emailValido = $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$emailValido) {
            $errores[] = 'Ingresa un email válido.';
        }

        if (strlen($password) < 6) {
            $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
        }

        // Solo consultamos duplicados si el email pasó el formato básico —
        // no tiene sentido pegarle a la BD con un email inválido.
        if ($emailValido && Usuario::emailExiste($email)) {
            $errores[] = 'Ya existe una cuenta registrada con ese email.';
        }

        if (!empty($errores)) {
            $old = ['nombre' => $nombre, 'email' => $email, 'telefono' => $telefono];
            $this->mostrarFormularioRegistro($errores, $old);
            return;
        }

        // El rol SIEMPRE se asigna aquí, en el backend — nunca se lee de $_POST.
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        Usuario::crear($nombre, $email, $telefono !== '' ? $telefono : null, $passwordHash);

        $_SESSION['flash_success'] = 'Cuenta creada correctamente. Ya puedes iniciar sesión.';
        header('Location: ' . BASE_PATH . '/iniciar-sesion');
        exit;
    }

    // GET /iniciar-sesion — TODO (HU02, la implementa tu compañero)
    public function mostrarFormularioLogin(): void
    {
        echo 'Formulario de login — pendiente de implementar (HU02).';
    }

    // POST /iniciar-sesion — TODO (HU02, la implementa tu compañero)
    public function login(): void
    {
        echo 'Procesar login — pendiente de implementar (HU02).';
    }
}
