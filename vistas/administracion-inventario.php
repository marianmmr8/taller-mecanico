<?php require __DIR__ . '/cabecera.php'; ?>

<p><a href="index.php?seccion=administracion">← Volver a Administración</a></p>

<?php if ($aviso): ?>
  <p class="aviso" role="status"><?= e($aviso) ?></p>
<?php endif; ?>
<?php if ($error): ?>
  <p class="error" role="alert"><?= e($error) ?></p>
<?php endif; ?>

<p>El mínimo de aviso es 4 unidades para cada pieza. Cuando el stock llega a ese nivel se indica que hay que reponer, pero no se bloquea el consumo: cada pieza asignada a una reparación descuenta sus unidades y no se puede asignar si el stock llega a cero. Al registrar una reposición se suman unidades y puedes ajustar el mínimo de aviso.</p>

<?php if (!$piezas): ?>
  <p class="empty-state">No hay piezas en el catálogo.</p>
<?php else: ?>
  <div class="table-scroll">
    <table class="inventory-table">
      <thead>
        <tr>
          <th>Pieza</th>
          <th>Referencia</th>
          <th class="num">Stock</th>
          <th>Estado</th>
          <th>Reposición</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($piezas as $pieza): ?>
          <?php
            $stock = (int) $pieza['stock'];
            $minimo = (int) $pieza['stock_minimo'];
            $stockBajo = $minimo > 0 && $stock <= $minimo;
          ?>
          <tr>
            <td>
              <strong><?= e($pieza['nombre']) ?></strong>
              <small><?= euros($pieza['precio']) ?> por unidad</small>
            </td>
            <td><?= e($pieza['referencia']) ?></td>
            <td class="num"><?= $stock ?></td>
            <td>
              <?php if ($stock === 0): ?>
                <span class="status status-low">Sin existencias</span>
              <?php elseif ($stockBajo): ?>
                <span class="status status-low">Reponer</span>
              <?php else: ?>
                <span class="status status-stock">Disponible</span>
              <?php endif; ?>
            </td>
            <td>
              <form method="post" action="index.php?seccion=administracion&amp;accion=reponer" class="replenish-form">
                <input type="hidden" name="csrf_token" value="<?= e(tokenCsrfAplicacion()) ?>">
                <input type="hidden" name="pieza_id" value="<?= (int) $pieza['id'] ?>">
                <label>
                  <span class="visually-hidden">Unidades que llegan</span>
                  <input type="number" name="unidades" min="1" step="1" placeholder="Unidades" required>
                </label>
                <label>
                  <span class="visually-hidden">Mínimo de aviso</span>
                  <input type="number" name="stock_minimo" min="0" step="1" value="<?= $minimo ?>" placeholder="Mínimo de aviso" required>
                </label>
                <button type="submit">Registrar</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<p class="admin-note">La instalación inicia las existencias en cero. Registra el inventario real antes de asignar piezas a reparaciones.</p>

<?php require __DIR__ . '/pie.php'; ?>
