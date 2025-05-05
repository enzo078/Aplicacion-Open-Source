<?= view('layouts/header') ?>

<?= $this->section('content') ?>
<h1>Editar Subtarea</h1>

<form action="<?= site_url('subtareas/update/' . $subtarea['id']) ?>" method="post">
    <?= csrf_field() ?>

    <label for="descripcion">Descripción:</label>
    <input type="text" name="descripcion" id="descripcion" value="<?= set_value('descripcion', $subtarea['descripcion']) ?>" required>

    <label for="estado">Estado:</label>
    <select name="estado" id="estado" required>
        <option value="Definida" <?= set_select('estado', 'Definida', $subtarea['estado'] === 'Definida') ?>>Definida</option>
        <option value="En proceso" <?= set_select('estado', 'En proceso', $subtarea['estado'] === 'En proceso') ?>>En proceso</option>
        <option value="Completada" <?= set_select('estado', 'Completada', $subtarea['estado'] === 'Completada') ?>>Completada</option>
    </select>

    <label for="prioridad">Prioridad:</label>
    <select name="prioridad" id="prioridad">
        <option value="Baja" <?= set_select('prioridad', 'Baja', $subtarea['prioridad'] === 'Baja') ?>>Baja</option>
        <option value="Normal" <?= set_select('prioridad', 'Normal', $subtarea['prioridad'] === 'Normal') ?>>Normal</option>
        <option value="Alta" <?= set_select('prioridad', 'Alta', $subtarea['prioridad'] === 'Alta') ?>>Alta</option>
    </select>

    <label for="comentario">Comentario (Opcional):</label>
    <textarea name="comentario" id="comentario"><?= set_value('comentario', $subtarea['comentario']) ?></textarea>

    <label for="responsable_id">Responsable:</label>
    <select name="responsable_id" id="responsable_id">
        <!-- Aquí deberías cargar los usuarios responsables disponibles -->
        <option value="1" <?= set_select('responsable_id', '1', $subtarea['responsable_id'] == 1) ?>>Responsable 1</option>
        <option value="2" <?= set_select('responsable_id', '2', $subtarea['responsable_id'] == 2) ?>>Responsable 2</option>
    </select>

    <button type="submit">Actualizar Subtarea</button>
</form>

<?= view('layouts/footer') ?>
