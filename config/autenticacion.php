<?php

require_once __DIR__ . '/config.php';

function iniciarSesionAplicacion(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function modoDemoAplicacion(): bool
{
    global $modoDemo;
    return isset($modoDemo) && $modoDemo === true;
}

function activarSesionDemo(): void
{
    if (!modoDemoAplicacion()) {
        return;
    }
    if (!isset($_SESSION['usuario'])) {
        $_SESSION['usuario'] = 'Usuario demo';
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
}

function credencialesLogin(): array
{
    $archivo = __DIR__ . '/auth_local.php';
    if (!is_file($archivo)) {
        throw new RuntimeException('Falta config/auth_local.php.');
    }

    $credenciales = require $archivo;
    if (
        !is_array($credenciales)
        || !isset($credenciales['usuario'], $credenciales['password_hash'])
        || !is_string($credenciales['usuario'])
        || !is_string($credenciales['password_hash'])
        || $credenciales['usuario'] === ''
        || $credenciales['password_hash'] === ''
    ) {
        throw new RuntimeException('La configuración local del login no es válida.');
    }

    return $credenciales;
}

function autenticarUsuario(string $usuario, string $contrasena): bool
{
    $credenciales = credencialesLogin();
    if (
        !hash_equals($credenciales['usuario'], $usuario)
        || !password_verify($contrasena, $credenciales['password_hash'])
    ) {
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['usuario'] = $credenciales['usuario'];
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    return true;
}

function tokenCsrfAplicacion(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function validarCsrfAplicacion($token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function destruirSesionAplicacion(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $cookie = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $cookie['path'],
            'domain' => $cookie['domain'],
            'secure' => $cookie['secure'],
            'httponly' => $cookie['httponly'],
            'samesite' => $cookie['samesite'] ?? 'Lax',
        ]);
    }

    session_destroy();
}
