<?php 
/**
 * @var array $reparaciones
* @var string $titulo 
 * @var string $estado
 * @var string $error
*/

require __DIR__ . '/cabecera.php'; ?>

<form method="get" action="index.php" class="linea">
  <input type="hidden" name="accion" value="listado">
  <label for="estado">Mostrar</label>
  <select name="estado" id="estado">
    <option value="abiertas" <?= $estado === 'abiertas' ? 'selected' : '' ?>>En el taller</option>
    <option value="finalizadas" <?= $estado === 'finalizadas' ? 'selected' : '' ?>>Finalizadas</option>
    <option value="todas" <?= $estado === 'todas' ? 'selected' : '' ?>>Todas</option>
  </select>
  <button>Filtrar</button>
</form>

<?php if ($error): ?>
  <p class="error"><?= e($error) ?></p>
<?php endif; ?>

<?php if (!$reparaciones): ?>
  <p class="empty-state">No hay reparaciones que coincidan con el filtro.</p>
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
                    <strong class="status status-open">En el taller</strong>

                <?php else: ?>
                    <span class="status status-closed">Salió el <?= fecha($re['fecha_salida']) ?></span>
                    <?php endif ; ?>   
            </td>

           </tr>
          <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<?php require __DIR__ . '/pie.php'; ?>