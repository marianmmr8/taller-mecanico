<?php 
/**
 * @var array $reparaciones
* @var string $titulo 
*/

require __DIR__ . '/cabecera.php'; ?>

<?php if (!$reparaciones): ?>
  <p>Todavía no hay ninguna reparación.</p>
<?php else: ?>
  <table>
    <thead>
      <tr><th>Entrada</th><th>Vehículo</th><th>Avería</th><th>Mecánico</th><th>Estado</th></tr>
    </thead>
    <tbody>
      <!-- TODO 3: una fila por reparación. Usa las funciones de ayudas.php:
           fecha() para las fechas y e() para escapar los textos.
           Vehículo: «Marca Modelo (matrícula)».
           Estado: «En el taller» en negrita, o «Salió el 21/10/2026» -->
           <?php  foreach($reparaciones as $re): ?>
           <tr>
            <td><?= fecha ($re['fecha_entrega']) ?></td>
            <td><?= e($re['marca'] . ' ' . $re['modelo'] ) ?> (<?= e($re['matricula']) ?>)</td>
            <td>
                <a href="index.php?accion=ver&amp;id=<?= $re['id'] ?>">
                <?= e($re['descripcion']) ?>
                </a>
            </td>
            <td><?= e($re['mecanico']) ?></td>
            <td>
                <?php if($re['fecha_salida'] === null): ?>
                    <strong>En el taller</strong>

                <?php else: ?>
                    Salio el <?=  fecha ($re['fecha_salida']) ?> 
                    <?php endif ; ?>   
            </td>

           </tr>
          <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<?php require __DIR__ . '/pie.php'; ?>