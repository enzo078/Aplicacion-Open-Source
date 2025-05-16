<?= view("layouts/header"); ?>
<form action="<?= site_url('/tareas/actualizar/' . $tarea['id']) ?>" method="post">
    <label for="asunto">Asunto</label>
    <input type="text" name="asunto" value="<?= old('asunto', $tarea['asunto']) ?>" required>

    <label for="descripcion">Descripción</label>
    <textarea name="descripcion"><?= old('descripcion', $tarea['descripcion']) ?></textarea>

    <label for="fecha_vencimiento">Fecha de Vencimiento</label>
    <input type="date" name="fecha_vencimiento" value="<?= old('fecha_vencimiento', $tarea['fecha_vencimiento']) ?>" required>

    <button type="submit">Actualizar tarea</button>
</form>
<?= view('layouts/footer') ?>