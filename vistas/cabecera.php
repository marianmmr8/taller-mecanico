<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($titulo) ?> · Taller Genil</title>
  <link rel="stylesheet" href="estilos.css">
</head>
<body>
  <header>
    <p class="marca">Taller Mecánico Genil</p>
    <nav>
      <a href="index.php">Reparaciones</a>
      <a href="index.php?accion=nueva">Nueva reparación</a>
    </nav>
  </header>
  <main>
    <h1><?= e($titulo) ?></h1>