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

        if ($emailValido && Usuario::emailExiste($email)) {
            $errores[] = 'Ya existe una cuenta registrada con ese email.';
        }

        if (!empty($errores)) {
            $old = ['nombre' => $nombre, 'email' => $email, 'telefono' => $telefono];
            $this->mostrarFormularioRegistro($errores, $old);
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        Usuario::crear($nombre, $email, $telefono !== '' ? $telefono : null, $passwordHash);

        $_SESSION['flash_success'] = 'Cuenta creada correctamente. Ya puedes iniciar sesión.';
        header('Location: ' . BASE_PATH . '/iniciar-sesion');
        exit;
    }

    // GET /iniciar-sesion
    public function mostrarFormularioLogin(array $errores = []): void
    {
        require __DIR__ . '/../views/auth/login.php';
    }

    // POST /iniciar-sesion — HU02
    public function login(): void
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $errores = [];
        $usuario = null;

        if ($email === '' || $password === '') {
            $errores[] = 'Ingresa tu email y contraseña.';
        } else {
            $usuario = Usuario::buscarPorEmail($email);
        }

        // Mensaje genérico a propósito (RNF04): no revela si el email existe o no,
        // ni si el problema fue el email o la contraseña.
        if (empty($errores) && (!$usuario || !password_verify($password, $usuario['password_hash']))) {
            $errores[] = 'Email o contraseña incorrectos.';
        }

        if (!empty($errores)) {
            $this->mostrarFormularioLogin($errores);
            return;
        }

        // Regenerar el ID de sesión al autenticar evita session fixation.
        session_regenerate_id(true);

        $_SESSION['usuario_id']     = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_rol']    = $usuario['rol'];

        // RF03: redirige según el rol.
        $destino = $usuario['rol'] === 'cliente' ? '/' : '/panel';
        header('Location: ' . BASE_PATH . $destino);
        exit;
    }

    // Bonus pequeño, no es una HU formal pero se necesita para poder probar
    // el login repetidamente sin borrar la sesión a mano.
    public function logout(): void
    {
        session_destroy();
        header('Location: ' . BASE_PATH . '/iniciar-sesion');
        exit;
    }
}
