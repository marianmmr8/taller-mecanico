<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#172333">
  <title><?= e($titulo) ?> · Nexo Motor</title>
  <link rel="stylesheet" href="estilos.css">
</head>
<body>
  <header class="site-header">
    <div class="header-inner">
      <a class="brand" href="index.php" aria-label="Nexo Motor, inicio">
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
      <nav aria-label="Navegación principal">
      <a href="index.php">Reparaciones</a>
      <a href="index.php?seccion=administracion">Administración</a>
      <a class="nav-primary" href="index.php?accion=nueva">Nueva reparación</a>
      <form method="post" action="logout.php" class="logout-form">
        <input type="hidden" name="csrf_token" value="<?= e(tokenCsrfAplicacion()) ?>">
        <button class="logout-button" type="submit">Salir, <?= e($_SESSION['usuario'] ?? '') ?></button>
      </form>
      </nav>
    </div>
  </header>
  <main class="main-content">
    <div class="page-heading">
      <span class="eyebrow">GESTIÓN DEL TALLER</span>
      <h1><?= e($titulo) ?></h1>
    </div>