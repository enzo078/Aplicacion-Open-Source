<?= view("layouts/header") ?>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h3><?= esc($tarea['asunto']) ?></h3>
        <small>Creada por: <?= esc($tarea['usuario_nombre']) ?> • Fecha límite: <?= $tarea['fecha_vencimiento'] ?></small>
    </div>
    <div class="card-body">
        <p class="card-text"><?= nl2br(esc($tarea['descripcion'])) ?></p>
        
        
        <div class="mb-3">
            <span class="badge bg-<?= $tarea['estado'] == 'completada' ? 'success' : 'warning' ?>">
                <?= ucfirst($tarea['estado']) ?>
            </span>
            <span class="badge bg-<?= $tarea['prioridad'] == 'alta' ? 'danger' : ($tarea['prioridad'] == 'media' ? 'warning' : 'secondary') ?>">
                Prioridad: <?= $tarea['prioridad'] ?>
            </span>
        </div>
    </div>
</div>


<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Subtareas</h4>
        <?php if ($tareaModel->puedeEditarTarea($tarea['id'], session()->get('id'))): ?>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaSubtareaModal">
                <i class="fas fa-plus"></i> Nueva Subtarea
            </button>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <ul class="list-group">
            <?php foreach ($subtareas as $subtarea): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <h6><?= esc($subtarea['descripcion']) ?></h6>
                        <small class="text-muted">
                            Responsable: <?= esc($subtarea['responsable_nombre'] ?? 'Sin asignar') ?>
                            • Estado: <span class="badge bg-<?= $subtarea['estado'] == 'completada' ? 'success' : 'warning' ?>">
                                <?= ucfirst($subtarea['estado']) ?>
                            </span>
                        </small>
                    </div>
                    <div class="btn-group">
                        <?php if ($subtareaModel->puedeEditarSubtarea($subtarea['id'], session()->get('id'))): ?>
                            <button class="btn btn-sm btn-outline-secondary edit-subtask" data-id="<?= $subtarea['id'] ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>


<div class="modal fade" id="nuevaSubtareaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('subtareas/crear') ?>" method="post">
                <input type="hidden" name="id_tarea" value="<?= $tarea['id'] ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Subtarea</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Asignar a</label>
                        <select name="responsable_id" class="form-select">
                            <option value="">Seleccionar colaborador</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?= $usuario['id'] ?>">
                                    <?= esc($usuario['nombre']) ?> (<?= esc($usuario['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="asignado_es_admin" id="permisosAdmin" value="1">
                        <label class="form-check-label" for="permisosAdmin">
                            Permitir que este colaborador edite la subtarea
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>