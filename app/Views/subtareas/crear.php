<?= view('layouts/header') ?>
<h2>Crear Subtarea para: <?= esc($tarea['titulo']) ?></h2>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('/subtareas/guardar') ?>" method="post">
        <?= csrf_field() ?>

        <input type="hidden" name="tarea_id" value="<?= esc($tarea['id']) ?>">

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <input type="text" class="form-control" name="descripcion" id="descripcion" required>
        </div>

        <div class="mb-3">
            <label for="prioridad" class="form-label">Prioridad</label>
            <select class="form-select" name="prioridad" id="prioridad">
                <option value="Alta">Alta</option>
                <option value="Media">Media</option>
                <option value="Baja">Baja</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" name="estado" id="estado">
                <option value="Definida">Definida</option>
                <option value="En proceso">En proceso</option>
                <option value="Completada">Completada</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="fecha_vencimiento" class="form-label">Fecha de vencimiento</label>
            <input type="date" class="form-control" name="fecha_vencimiento" id="fecha_vencimiento">
        </div>

        <div class="mb-3">
            <label for="color" class="form-label">Color</label>
            <input type="color" class="form-control form-control-color" name="color" id="color">
        </div>

        <div class="mb-3">
            <label for="responsable_id" class="form-label">Responsable</label>
            <select class="form-select" name="responsable_id" id="responsable_id" required>
                <option value="">Seleccionar responsable</option>
                <?php foreach ($responsables as $usuario): ?>
                    <option value="<?= $usuario['id'] ?>">
                        <?= esc($usuario['nombre']) ?> (<?= esc($usuario['email']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="comentario" class="form-label">Comentario (opcional)</label>
            <textarea name="comentario" id="comentario" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Crear Subtarea</button>
        <a href="<?= base_url('/tareas/' . $tarea['id']) ?>" class="btn btn-secondary">Cancelar</a>
    </form>

<?= view('layouts/footer') ?>
