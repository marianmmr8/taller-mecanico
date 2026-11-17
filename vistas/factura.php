<?php
/**
 * @var array $reparacion
 * @var array $piezas
 * @var float $base
 * @var float $iva
 * @var float $total
 * @var string $numeroFactura
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Factura <?= e($numeroFactura) ?> · Nexo Motor</title>
  <link rel="stylesheet" href="estilos.css">
</head>
<body class="invoice-page">
  <main class="invoice-sheet">
    <header class="invoice-heading">
      <a class="brand invoice-brand" href="index.php" aria-label="Nexo Motor">
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
      <div class="invoice-number">
        <span>FACTURA</span>
        <strong><?= e($numeroFactura) ?></strong>
        <small>Fecha: <?= fecha($reparacion['fecha_salida']) ?></small>
      </div>
    </header>

    <section class="invoice-parties">
      <div>
        <span class="eyebrow">CLIENTE</span>
        <strong><?= e($reparacion['cliente_nombre'] . ' ' . $reparacion['cliente_apellido']) ?></strong>
        <span><?= e($reparacion['telefono']) ?></span>
      </div>
      <div>
        <span class="eyebrow">VEHÍCULO</span>
        <strong><?= e($reparacion['marca'] . ' ' . $reparacion['modelo']) ?></strong>
        <span>Matrícula <?= e($reparacion['matricula']) ?></span>
      </div>
    </section>

    <section class="invoice-description">
      <span class="eyebrow">REPARACIÓN</span>
      <p><?= e($reparacion['descripcion']) ?></p>
      <small>Entrada: <?= fecha($reparacion['fecha_entrega']) ?> · Salida: <?= fecha($reparacion['fecha_salida']) ?></small>
    </section>

    <table class="invoice-table">
      <thead>
        <tr>
          <th>Concepto</th>
          <th class="num">Unidades</th>
          <th class="num">Precio</th>
          <th class="num">Importe</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            Mano de obra
            <?php if ((float) $reparacion['horas_mano_obra'] > 0): ?>
              <small><?= e((string) $reparacion['horas_mano_obra']) ?> h adicionales</small>
            <?php endif; ?>
          </td>
          <td class="num">—</td>
          <td class="num">—</td>
          <td class="num"><?= euros($reparacion['mano_obra']) ?></td>
        </tr>
        <?php foreach ($piezas as $pieza): ?>
          <tr>
            <td><?= e($pieza['nombre']) ?></td>
            <td class="num"><?= (int) $pieza['unidades'] ?></td>
            <td class="num"><?= euros($pieza['precio']) ?></td>
            <td class="num"><?= euros($pieza['importe']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <section class="invoice-summary">
      <p><span>Base imponible</span><strong><?= euros($base) ?></strong></p>
      <p><span>IVA (21 %)</span><strong><?= euros($iva) ?></strong></p>
      <p class="invoice-grand-total"><span>Total</span><strong><?= euros($total) ?></strong></p>
    </section>

    <p class="invoice-thanks">Gracias por confiar en Nexo Motor.</p>

    <div class="invoice-actions">
      <a class="button-link button-secondary" href="index.php?accion=ver&amp;id=<?= (int) $reparacion['id'] ?>">Volver a la reparación</a>
      <button type="button" onclick="window.print()">Imprimir factura</button>
    </div>
  </main>
</body>
</html>
