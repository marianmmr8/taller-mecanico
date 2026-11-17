<?php 
/**
 * @var array $reparacion
*@var array $piezas
*@var float $total
*@var string $titulo
* @var string $aviso
* @var string $error
* @var array $catalogoPiezas

 */
require __DIR__ . '/cabecera.php'; ?>

<?php if ($aviso): ?>
  <p class="aviso"><?= e($aviso) ?></p>
<?php endif; ?>

<?php if ($error): ?>
  <p class="error"><?= e($error) ?></p>
<?php endif; ?>

<dl>
  <dt>Vehículo</dt>
  <dd><?= e($reparacion['marca'] . ' ' . $reparacion['modelo']) ?> (<?= e($reparacion['matricula']) ?>)</dd>
  <!-- TODO 7: con el mismo formato, añade: Cliente («Nombre Apellidos · teléfono»),
       Mecánico, Avería, Entrada y Salida («En el taller» si todavía no ha salido) -->
    <dt>Cliente</dt>
    <dd><?=  e($reparacion['cliente_nombre']) . ' ' . $reparacion[ 'cliente_apellido'] ?> . <?= $reparacion ['telefono'] ?> </dd>
    <dt>Mecánico</dt>
    <dd><?= $reparacion ['mecanico'] ?></dd>
    <dt>Avería</dt>
    <dd><?=  $reparacion ['descripcion'] ?></dd>
    <dt>Entrada</dt>
     <dd><?=  $reparacion ['fecha_entrega'] ?></dd>
     <dt>Salida</dt>
     <dd><?= $reparacion['fecha_salida'] === null ? 'En el taller' : fecha($reparacion ['fecha_salida']) ?></dd>
</dl>

<h2>Piezas</h2>
<!-- TODO 8: si no hay piezas, «No se ha usado ninguna pieza.»
     Si hay, una tabla: Pieza, Unidades, Precio e Importe.
     Usa euros() para el dinero y la clase «num» en las columnas de números -->

     <?php if (!$piezas): ?>
        <p>No se ha usado ninguna pieza</p>
      <?php else :?>
    
        <table>
            <thead>
                <tr>
                 <th>Piezas</th>
                 <th class="num">Unidades</th>
                  <th class="num">Precio</th>
                   <th class="num">Importe</th>
                   <?php if ($reparacion['fecha_salida'] === null): ?>
                     <th>Acción</th>
                   <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($piezas as $pi) :?>
                    <tr>
                    <td><?= e ($pi['nombre']) ?></td>
                    <td class="num"><?= $pi['unidades'] ?></td>
                    <td class="num"><?= euros($pi['precio']) ?></td>
                    <td class="num"><?= euros($pi['importe']) ?></td>
                    <?php if ($reparacion['fecha_salida'] === null): ?>
                      <td>
                        <form method="post" action="index.php?accion=quitarPieza" class="piece-remove-form">
                          <input type="hidden" name="csrf_token" value="<?= e(tokenCsrfAplicacion()) ?>">
                          <input type="hidden" name="id" value="<?= (int) $reparacion['id'] ?>">
                          <input type="hidden" name="pieza_id" value="<?= (int) $pi['id_pieza'] ?>">
                          <button class="button-danger" type="submit">Quitar</button>
                        </form>
                      </td>
                    <?php endif; ?>

                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        <?php endif; ?>

<?php if ($reparacion['fecha_salida'] === null): ?>
  <h2>Añadir pieza</h2>
  <form method="post" class="linea" action="index.php?accion=anadirPieza&amp;id=<?= (int) $reparacion['id'] ?>">
    <input type="hidden" name="csrf_token" value="<?= e(tokenCsrfAplicacion()) ?>">
    <label for="pieza_id">Pieza</label>
    <select name="pieza_id" id="pieza_id" required>
      <option value="">Elige una pieza…</option>
      <?php foreach ($catalogoPiezas as $pieza): ?>
        <option value="<?= (int) $pieza['id'] ?>" <?= (int) $pieza['stock'] < 1 ? 'disabled' : '' ?>>
          <?= e($pieza['nombre'] . ' · ' . $pieza['referencia'] . ' · ' . euros($pieza['precio']) . ' · Stock: ' . $pieza['stock']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label for="unidades">Unidades</label>
    <input type="number" name="unidades" id="unidades" min="1" step="1" value="1" required>
    <button>Añadir pieza</button>
  </form>

  <h2>Añadir mano de obra</h2>
  <form method="post" class="linea" action="index.php?accion=anadirManoObra">
    <input type="hidden" name="csrf_token" value="<?= e(tokenCsrfAplicacion()) ?>">
    <input type="hidden" name="id" value="<?= (int) $reparacion['id'] ?>">
    <label for="horas">Horas</label>
    <input type="number" name="horas" id="horas" min="0.01" step="0.25" required>

    <label for="tarifa_hora">Tarifa por hora (€)</label>
    <input type="number" name="tarifa_hora" id="tarifa_hora" min="0" step="0.01" required>
    <button>Añadir mano de obra</button>
  </form>

  <form method="post" class="linea" action="index.php?accion=cerrar">
    <input type="hidden" name="csrf_token" value="<?= e(tokenCsrfAplicacion()) ?>">
    <input type="hidden" name="id" value="<?= (int) $reparacion['id'] ?>">
    <button>Registrar salida</button>
  </form>
<?php else: ?>
  <p><a class="button-link" href="index.php?accion=factura&amp;id=<?= (int) $reparacion['id'] ?>">Imprimir factura</a></p>
<?php endif; ?>

<!-- TODO 9: dos párrafos: «Mano de obra: 80,00 €» y, con la clase «total»,
     «Total: 145,00 €» -->
     <p>Mano de obra: <?=  euros ($reparacion['mano_obra']) ?></p>
     <?php if ((float) $reparacion['horas_mano_obra'] > 0): ?>
       <p>Horas adicionales: <?= e((string) $reparacion['horas_mano_obra']) ?> h</p>
     <?php endif; ?>
     <p class="total">Total :<?= euros ($total) ?></p>


<?php require __DIR__ . '/pie.php'; ?>