<?php 
/**
 * @var array $reparacion
*@var array $piezas
*@var float $total
*@var string $titulo
 */





require __DIR__ . '/cabecera.php'; ?>

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
                </tr>
            </thead>
            <tbody>
                <?php foreach($piezas as $pi) :?>
                    <tr>
                    <td><?= e ($pi['nombre']) ?></td>
                    <td class="num"><?= $pi['unidades'] ?></td>
                    <td class="num"><?= euros($pi['precio']) ?></td>
                    <td class="num"><?= euros($pi['importe']) ?></td>

                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        <?php endif; ?>

<!-- TODO 9: dos párrafos: «Mano de obra: 80,00 €» y, con la clase «total»,
     «Total: 145,00 €» -->
     <p>Mano de obra: <?=  euros ($reparacion['mano_obra']) ?></p>
     <p class="total">Total :<?= euros ($total) ?></p>


<?php require __DIR__ . '/pie.php'; ?>