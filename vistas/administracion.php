<?php require __DIR__ . '/cabecera.php'; ?>

<p>Consulta las facturas del periodo o gestiona las existencias del almacén.</p>

<div class="admin-menu">
  <a class="admin-card" href="index.php?seccion=administracion&amp;accion=ventas">
    <span class="eyebrow">GESTORÍA</span>
    <strong>Ventas y facturas</strong>
    <span>Resumen por fechas con base imponible, IVA y enlaces a las facturas.</span>
  </a>
  <a class="admin-card" href="index.php?seccion=administracion&amp;accion=inventario">
    <span class="eyebrow">ALMACÉN</span>
    <strong>Piezas y existencias</strong>
    <span>Consulta el stock, los mínimos y registra reposiciones.</span>
  </a>
</div>

<?php require __DIR__ . '/pie.php'; ?>
