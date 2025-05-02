<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul>
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?= view('layouts/header') ?>
<body>
<form action="<?= site_url('/tareas/create') ?>" method="post">
    <label for="asunto">Asunto</label>
    <input type="text" name="asunto" value="<?= old('asunto') ?>" required>
    <?php if (isset($validation) && $validation->hasError('asunto')): ?>
        <div class="text-red-500 text-sm"><?= $validation->getError('asunto') ?></div>
    <?php endif; ?>

    <label for="descripcion">Descripción</label>
    <textarea name="descripcion"><?= old('descripcion') ?></textarea>
    <?php if (isset($validation) && $validation->hasError('descripcion')): ?>
        <div class="text-red-500 text-sm"><?= $validation->getError('descripcion') ?></div>
    <?php endif; ?>

    <label for="fecha_vencimiento">Fecha de Vencimiento</label>
    <input type="date" name="fecha_vencimiento" value="<?= old('fecha_vencimiento') ?>" required>
    <?php if (isset($validation) && $validation->hasError('fecha_vencimiento')): ?>
        <div class="text-red-500 text-sm"><?= $validation->getError('fecha_vencimiento') ?></div>
    <?php endif; ?>

    <label for="fecha_recordatorio">Fecha de Recordatorio (opcional)</label>
    <input type="date" name="fecha_recordatorio" value="<?= old('fecha_recordatorio') ?>">
    <?php if (isset($validation) && $validation->hasError('fecha_recordatorio')): ?>
        <div class="text-red-500 text-sm"><?= $validation->getError('fecha_recordatorio') ?></div>
    <?php endif; ?>

    <label for="prioridad">Prioridad</label>
    <select name="prioridad" id="prioridad" onchange="cambiarColorPrioridad()">
        <option value="alta" <?= old('prioridad') == 'alta' ? 'selected' : '' ?>>Alta</option>
        <option value="normal" <?= old('prioridad') == 'normal' ? 'selected' : '' ?>>Normal</option>
        <option value="baja" <?= old('prioridad') == 'baja' ? 'selected' : '' ?>>Baja</option>
    </select>

    <button type="submit">Crear Tarea</button>
</form>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('formTarea');

        const asunto = document.getElementById('asunto');
        const descripcion = document.getElementById('descripcion');
        const vencimiento = document.getElementById('fecha_vencimiento');
        const recordatorio = document.getElementById('fecha_recordatorio');

        const errorAsunto = document.getElementById('errorAsunto');
        const errorDescripcion = document.getElementById('errorDescripcion');
        const errorVencimiento = document.getElementById('errorVencimiento');
        const errorRecordatorio = document.getElementById('errorRecordatorio');

        form.addEventListener('submit', function (e) {
            let valido = true;

            // Limpiar errores
            errorAsunto.textContent = '';
            errorDescripcion.textContent = '';
            errorVencimiento.textContent = '';
            errorRecordatorio.textContent = '';

            // Validaciones
            if (asunto.value.trim().length < 3) {
                errorAsunto.textContent = 'El asunto debe tener al menos 3 caracteres.';
                valido = false;
            }

            if (descripcion.value.trim().length === 0) {
                errorDescripcion.textContent = 'La descripción es obligatoria.';
                valido = false;
            }

            if (vencimiento.value === '') {
                errorVencimiento.textContent = 'La fecha de vencimiento es obligatoria.';
                valido = false;
            }

            if (recordatorio.value && !/^\d{4}-\d{2}-\d{2}$/.test(recordatorio.value)) {
                errorRecordatorio.textContent = 'La fecha de recordatorio debe tener el formato YYYY-MM-DD.';
                valido = false;
            }

            if (!valido) {
                e.preventDefault(); // Evita el envío si hay errores
            }
        });
    });
</script>


</body>
<?= view('layouts/footer') ?>