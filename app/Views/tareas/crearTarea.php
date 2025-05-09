<?= view('layouts/header') ?>

<div class="container">
    <h2>Crear Nueva Tarea</h2>
    
    <!-- Mostrar errores -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <p><?= esc($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('tareas/create') ?>" method="post">
        <!-- Campo OCULTO con el ID de usuario -->
        <input type="hidden" name="id_usuario" value="<?= session()->get('id') ?>">
        
        <div class="mb-3">
            <label for="asunto" class="form-label">Asunto</label>
            <input type="text" class="form-control" name="asunto" id="asunto" value="<?= old('asunto') ?>" required>
            <?php if (isset($validation) && $validation->hasError('asunto')): ?>
                <div class="invalid-feedback"><?= $validation->getError('asunto') ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" name="descripcion" id="descripcion" rows="3"><?= old('descripcion') ?></textarea>
        </div>

        <div class="mb-3">
            <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
            <input type="date" class="form-control" name="fecha_vencimiento" id="fecha_vencimiento" value="<?= old('fecha_vencimiento') ?>" required>
            <?php if (isset($validation) && $validation->hasError('fecha_vencimiento')): ?>
                <div class="invalid-feedback"><?= $validation->getError('fecha_vencimiento') ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="fecha_recordatorio" class="form-label">Fecha de Recordatorio (opcional)</label>
            <input type="date" class="form-control" name="fecha_recordatorio" id="fecha_recordatorio" value="<?= old('fecha_recordatorio') ?>">
        </div>

        <div class="mb-3">
            <label for="prioridad" class="form-label">Prioridad</label>
            <select class="form-select" name="prioridad" id="prioridad">
                <option value="Baja" <?= old('prioridad') == 'Baja' ? 'selected' : '' ?>>Baja</option>
                <option value="Normal" <?= old('prioridad') == 'Normal' ? 'selected' : '' ?>>Normal</option>
                <option value="Alta" <?= old('prioridad') == 'Alta' ? 'selected' : '' ?>>Alta</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Crear Tarea</button>
        <a href="<?= site_url('/dashboard') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>


<script>
// Validación básica del lado del cliente
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
});
</script>

