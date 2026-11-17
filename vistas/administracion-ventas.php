<?php require __DIR__ . '/cabecera.php'; ?>

<p><a href="index.php?seccion=administracion">← Volver a Administración</a></p>

<form method="get" action="index.php" class="linea">
  <input type="hidden" name="seccion" value="administracion">
  <input type="hidden" name="accion" value="ventas">
  <label for="desde">Desde</label>
  <input type="date" name="desde" id="desde" value="<?= e($desde) ?>" required>
  <label for="hasta">Hasta</label>
  <input type="date" name="hasta" id="hasta" value="<?= e($hasta) ?>" required>
  <button type="submit">Consultar</button>
</form>

<?php if ($error): ?>
  <p class="error" role="alert"><?= e($error) ?></p>
<?php endif; ?>

<div class="summary-grid">
  <div class="summary-card">
    <span>Base imponible</span>
    <strong><?= euros($baseTotal) ?></strong>
  </div>
  <div class="summary-card">
    <span>IVA (21 %)</span>
    <strong><?= euros($ivaTotal) ?></strong>
  </div>
  <div class="summary-card summary-total">
    <span>Total facturado</span>
    <strong><?= euros($total) ?></strong>
  </div>
</div>

<h2>Facturas del periodo</h2>
<?php if (!$facturas): ?>
  <p class="empty-state">No hay reparaciones cerradas en esas fechas.</p>
<?php else: ?>
  <div class="table-scroll">
    <table>
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Factura</th>
          <th>Cliente</th>
          <th>Matrícula</th>
          <th class="num">Base</th>
          <th class="num">IVA</th>
          <th class="num">Total</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($facturas as $factura): ?>
          <tr>
            <td><?= fecha($factura['fecha_salida']) ?></td>
            <td><a href="index.php?accion=factura&amp;id=<?= (int) $factura['id'] ?>"><?= e($factura['numero']) ?></a></td>
            <td><?= e($factura['cliente_nombre'] . ' ' . $factura['cliente_apellidos']) ?></td>
            <td><?= e($factura['matricula']) ?></td>
            <td class="num"><?= euros($factura['base']) ?></td>
            <td class="num"><?= euros($factura['iva']) ?></td>
            <td class="num"><?= euros($factura['total']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<p class="admin-note">Resumen de reparaciones cerradas en el intervalo. Comprueba los importes y los datos fiscales antes de enviarlo a la gestoría.</p>

<?php require __DIR__ . '/pie.php'; ?>
