<?php
require __DIR__ . '/config/autenticacion.php';

iniciarSesionAplicacion();
activarSesionDemo();
if (isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$loginDisponible = true;
try {
    credencialesLogin();
} catch (RuntimeException $e) {
    http_response_code(500);
    $loginDisponible = false;
    $error = 'El acceso no está configurado. Crea config/auth_local.php a partir del archivo de ejemplo.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $loginDisponible) {
    if (!validarCsrfAplicacion($_POST['csrf_token'] ?? null)) {
        http_response_code(403);
        $error = 'La sesión del formulario ha caducado. Recarga la página e inténtalo de nuevo.';
    } else {
        $usuario = $_POST['usuario'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';
        if (
            is_string($usuario)
            && is_string($contrasena)
            && autenticarUsuario(trim($usuario), $contrasena)
        ) {
            header('Location: index.php');
            exit;
        }

        $error = 'Usuario o contraseña incorrectos.';
    }
}

$csrfToken = tokenCsrfAplicacion();
header('Cache-Control: no-store');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#172333">
  <title>Iniciar sesión · Nexo Motor</title>
  <link rel="stylesheet" href="estilos.css">
</head>
<body class="login-page">
  <main class="login-card">
    <a class="brand login-brand" href="login.php" aria-label="Nexo Motor">
      <span class="brand-mark" aria-hidden="true">
        <svg viewBox="0 0 48 48" focusable="false">
          <rect x="1" y="1" width="46" height="46" rx="15" fill="currentColor"/>
          <path d="M10 29h28l-2.5-8a3 3 0 0 0-2.9-2.1H15.4a3 3 0 0 0-2.9 2.1L10 29Zm3 0v4m22-4v4M16 24h16" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.6"/>
          <circle cx="16" cy="31" r="2" fill="#fff"/>
          <circle cx="32" cy="31" r="2" fill="#fff"/>
        </svg>
      </span>
      <span class="brand-copy">
        <strong>Nexo Motor</strong>
        <small>TALLER · SERVICIO</small>
      </span>
    </a>

    <div class="login-heading">
      <span class="eyebrow">ÁREA DE TRABAJO</span>
      <h1>Iniciar sesión</h1>
      <p>Accede a la gestión del taller.</p>
    </div>

    <?php if (($_GET['salir'] ?? '') === '1'): ?>
      <p class="aviso" role="status">Sesión cerrada.</p>
    <?php endif; ?>

    <?php if ($error): ?>
      <p class="error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="post" class="login-form">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
      <label for="usuario">Usuario</label>
      <input type="text" name="usuario" id="usuario" autocomplete="username" required autofocus>

      <label for="contrasena">Contraseña</label>
      <input type="password" name="contrasena" id="contrasena" autocomplete="current-password" required>

      <button type="submit" <?= $loginDisponible ? '' : 'disabled' ?>>Entrar</button>
    </form>
  </main>
</body>
</html>
