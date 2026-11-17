<?php
/**
 * @var array $errores
 * @var array $mecanicos
* @var array $vehiculos
*/


require __DIR__ . '/cabecera.php'; ?>
<!-- // comprueba si hay errores y si hay los muestra en una lista -->
<?php if ($errores): ?>
  <ul class="error">
    <?php foreach ($errores as $error): ?>
      <li><?= e($error) ?></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
<!-- // option para mostrar los vehiculos, si ya habia alguno seleccionado se muestra el seleccionado -->
<form method="post" action="index.php?accion=guardar" class="alta">
  <label for="vehiculo_id">Vehículo</label>
  <select name="vehiculo_id" id="vehiculo_id" required>
    <option value="">Elige un vehículo…</option>
    <?php foreach ($vehiculos as $v): ?>
      <option value="<?= $v['id'] ?>" <?= ($valores['vehiculo_id'] ?? '') == $v['id'] ? 'selected' : '' ?>>
        <?= e($v['marca'] . ' ' . $v['modelo'] . ' · ' . $v['matricula'] . ' · ' . $v['nombre'] . ' ' . $v['apellidos']) ?>
      </option>
    <?php endforeach; ?>
  </select>
<!-- //option para mostrar los mecanicos , si ya habia alguno seleccionado se muestra seleccionado -->
  <label for="mecanico_id">Mecánico</label>
  <select name="mecanico_id" id="mecanico_id" required>
    <option value="">Elige un mecánico…</option>
    <?php foreach ($mecanicos as $m): ?>
      <option value="<?= $m['id'] ?>" <?= ($valores['mecanico_id'] ?? '') == $m['id'] ? 'selected' : '' ?>>
        <?= e($m['nombre'] . ' (' . $m['especialidad'] . ')') ?>
      </option>
    <?php endforeach; ?>
  </select>
<!-- // para la descripcion de la averia , si ya habia una descripcion se vuelve a mostrar -->
  <label for="descripcion">Avería</label>
  <textarea name="descripcion" id="descripcion" rows="3" maxlength="250" required><?= e($valores['descripcion'] ?? '') ?></textarea>
<!-- // imput para el importe de la mano de obra  -->
  <label for="mano_obra">Mano de obra (€)</label>
  <input type="number" name="mano_obra" id="mano_obra" min="0" step="0.01" required value="<?= e((string) ($valores['mano_obra'] ?? '')) ?>">

  <button>Abrir la reparación</button>
</form>

<?php require __DIR__ . '/pie.php'; ?>