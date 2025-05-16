<?= view("layouts/header") ?>

<style>
.modal-content .priority-label {
    padding: 4px 12px;
    border-radius: 20px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    font-size: 0.9rem;
    margin-top: 5px;
}

.modal-content .priority-high {
    background-color: #ffebee;
    color: #c62828;
    border: 1px solid #ef9a9a;
}

.modal-content .priority-medium {
    background-color: #fff8e1;
    color: #f57f17;
    border: 1px solid #ffcc80;
}

.modal-content .priority-low {
    background-color: #e8f5e9;
    color: #2e7d32;
    border: 1px solid #a5d6a7;
}

.priority-select option[value="Alta"] {
    color: #c62828;
    font-weight: 500;
}

.priority-select option[value="Normal"] {
    color: #f57f17;
    font-weight: 500;
}

.priority-select option[value="Baja"] {
    color: #2e7d32;
    font-weight: 500;
}
</style>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <ul>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Tarea -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3><?= esc($tarea['asunto']) ?></h3>
                <small>Creada por: <?= esc($tarea['usuario_nombre']) ?> • Fecha límite: <?= $tarea['fecha_vencimiento'] ?></small>
            </div>
            <?php 
            $esCreador = $tarea['id_usuario'] == session()->get('id');
            $esAdmin = session()->get('rol') == 'admin';
            if ($esCreador || $esAdmin): ?>
                <button class="btn btn-sm btn-light edit-task" title="Editar"
                    data-bs-toggle="modal" 
                    data-bs-target="#editarTareaModal"
                    data-id="<?= $tarea['id'] ?>"
                    data-asunto="<?= esc($tarea['asunto']) ?>"
                    data-descripcion="<?= esc($tarea['descripcion']) ?>"
                    data-prioridad="<?= esc($tarea['prioridad']) ?>"
                    data-estado="<?= esc($tarea['estado']) ?>"
                    data-fecha_vencimiento="<?= esc($tarea['fecha_vencimiento']) ?>"
                    data-fecha_recordatorio="<?= esc($tarea['fecha_recordatorio'] ?? '') ?>"
                    data-color="<?= esc($tarea['color'] ?? '') ?>"
                    data-archivada="<?= $tarea['archivada'] ?? 0 ?>">
                    <i class="fas fa-edit"></i> 
                </button>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <div class="flex-grow-1">
                <p class="card-text"><?= nl2br(esc($tarea['descripcion'])) ?></p>
                <div class="mt-3">
                    <span class="badge status-badge bg-secondary">
                        <?= esc($tarea['estado']) ?>
                    </span>
                    <?php
                    $prioridadColors = [
                        'Alta' => 'danger',
                        'Normal' => 'warning',
                        'Baja' => 'success'
                    ];
                    $color = $prioridadColors[$tarea['prioridad']] ?? 'secondary';
                    ?>
                    <span class="badge bg-<?= $color ?> ms-2">
                        Prioridad: <?= esc($tarea['prioridad']) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar Tarea -->
<div class="modal fade" id="editarTareaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('tareas/actualizar/'.$tarea['id']) ?>" method="post">
                <input type="hidden" name="id" id="edit_task_id">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Tarea</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Asunto</label>
                        <textarea name="asunto" id="edit_asunto" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" id="edit_descripcion" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prioridad</label>
                        <select name="prioridad" class="form-select" id="edit_prioridad">
                            <option value="Alta">Alta</option>
                            <option value="Normal">Normal</option>
                            <option value="Baja">Baja</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha Vencimiento</label>
                        <input type="date" name="fecha_vencimiento" id="edit_fecha_vencimiento" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha Recordatorio</label>
                        <input type="date" name="fecha_recordatorio" id="edit_fecha_recordatorio" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Lista de subtareas -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Subtareas</h4>
        <?php if ($esCreador || $esAdmin): ?>
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
                        <h4><?= esc($subtarea['asunto']) ?></h4>
                        <h6><?= esc($subtarea['descripcion']) ?></h6>
                        <small class="text-muted">
                           Responsable: <?= esc($subtarea['responsable_nombre'] ?? 'Sin asignar') ?>
                            • Estado:
                            <?php if ($subtarea['id_responsable'] == session()->get('id')): ?>
                                <form method="post" class="estado-form d-inline" data-id="<?= $subtarea['id'] ?>" action="<?= site_url('subtareas/cambiarEstado') ?>">
                                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                    <input type="hidden" name="id" value="<?= $subtarea['id'] ?>" />
                                    <select name="estado" class="form-select form-select-sm d-inline-block w-auto estado-select">
                                        <option value="En Proceso" <?= $subtarea['estado'] == 'En Proceso' ? 'selected' : '' ?>>En Proceso</option>
                                        <option value="Completada" <?= $subtarea['estado'] == 'Completada' ? 'selected' : '' ?>>Completada</option>
                                    </select>
                                </form>
                            <?php else: ?>
                                <span class="badge bg-<?= 
                                    $subtarea['estado'] == 'Completada' ? 'success' : 
                                    ($subtarea['estado'] == 'En proceso' ? 'warning' : 'secondary')
                                ?>"><?= ucfirst(str_replace('_', ' ', $subtarea['estado'])) ?></span>
                            <?php endif; ?>

                            • Fecha Vencimiento: <?= esc($subtarea['fecha_vencimiento']) ?>
                            • Prioridad: <span class="badge bg-<?= $prioridadColors[$subtarea['prioridad']] ?? 'secondary' ?>"><?= esc($subtarea['prioridad']) ?></span>
                        </small>
                    </div>
                    <div class="btn-group">
                        <?php 
                        $puedeEditarSubtarea = false;
                        if ($esCreador || $esAdmin) {
                            $puedeEditarSubtarea = true;
                        } elseif ($subtarea['id_responsable'] == session()->get('id') && ($subtarea['asignado_es_admin'] ?? 0)) {
                            $puedeEditarSubtarea = true;
                        }
                        
                        if ($puedeEditarSubtarea): ?>
                            <button class="btn btn-sm btn-light edit-subtask-btn" title="Editar subtarea"
                                data-bs-toggle="modal"
                                data-bs-target="#editarSubtareaModal"
                                data-id="<?= $subtarea['id'] ?>"
                                data-asunto="<?= esc($subtarea['asunto']) ?>"
                                data-descripcion="<?= esc($subtarea['descripcion']) ?>"
                                data-prioridad="<?= esc($subtarea['prioridad']) ?>"
                                data-estado="<?= esc($subtarea['estado']) ?>"
                                data-fecha_vencimiento="<?= esc($subtarea['fecha_vencimiento']) ?>"
                                data-fecha_recordatorio="<?= esc($subtarea['fecha_recordatorio'] ?? '') ?>"
                                data-id_responsable="<?= esc($subtarea['id_responsable'] ?? '') ?>"
                                data-asignado_es_admin="<?= esc($subtarea['asignado_es_admin'] ?? 0) ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                        <?php endif; ?>
                        
                        <?php if ($esCreador || $esAdmin): ?>
                            <button class="btn btn-sm btn-outline-danger delete-subtask-btn" title="Eliminar subtarea"
                                data-bs-toggle="modal"
                                data-bs-target="#confirmarEliminarSubtareaModal"
                                data-id="<?= $subtarea['id'] ?>">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<!-- Modal para crear nueva subtarea -->
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
                        <label class="form-label">Asunto</label>
                        <textarea name="asunto" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prioridad</label>
                        <select name="prioridad" class="form-select priority-select" id="prioritySelect">
                            <option value="Baja">Baja</option>
                            <option value="Normal" selected>Normal</option>
                            <option value="Alta">Alta</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha Vencimiento</label>
                        <input type="date" name="fecha_vencimiento" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Asignar a</label>
                        <select name="id_responsable" class="form-select">
                            <option value="">Seleccionar colaborador</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?= $usuario['id'] ?>">
                                    <?= esc($usuario['username']) ?> (<?= esc($usuario['email']) ?>)
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

<!-- Modal Editar Subtarea -->
<div class="modal fade" id="editarSubtareaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('subtareas/update/'.$subtarea['id']) ?>" method="post" id="formEditarSubtarea">
                <input type="hidden" name="id" id="edit_subtarea_id">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Subtarea</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Asunto</label>
                        <textarea name="asunto" id="edit_subtarea_asunto" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" id="edit_subtarea_descripcion" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prioridad</label>
                        <select name="prioridad" id="edit_subtarea_prioridad" class="form-select" required>
                            <option value="Alta">Alta</option>
                            <option value="Normal">Normal</option>
                            <option value="Baja">Baja</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" id="edit_subtarea_estado" class="form-select" required>
                            <option value="En proceso">En proceso</option>
                            <option value="Completada">Completada</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha Vencimiento</label>
                        <input type="date" name="fecha_vencimiento" id="edit_subtarea_fecha_vencimiento" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha Recordatorio</label>
                        <input type="date" name="fecha_recordatorio" id="edit_subtarea_fecha_recordatorio" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Asignar a</label>
                        <select name="id_responsable" id="edit_subtarea_responsable" class="form-select">
                            <option value="">Seleccionar colaborador</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?= $usuario['id'] ?>">
                                    <?= esc($usuario['username']) ?> (<?= esc($usuario['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="asignado_es_admin" id="edit_subtarea_permisos_admin" value="1">
                        <label class="form-check-label" for="edit_subtarea_permisos_admin">
                            Permitir que este colaborador edite la subtarea
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Confirmar eliminación subtarea (único) -->
<div class="modal fade" id="confirmarEliminarSubtareaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('subtareas/eliminar') ?>" method="post" id="formEliminarSubtarea">
                <input type="hidden" name="id" id="idEliminarSubtarea">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>¿Seguro que quieres eliminar esta subtarea? Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejo del modal de edición de subtarea
    const editSubtareaModal = new bootstrap.Modal(document.getElementById('editarSubtareaModal'));
    
    document.querySelectorAll('.edit-subtask-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('edit_subtarea_id').value = this.getAttribute('data-id');
            document.getElementById('edit_subtarea_asunto').value = this.getAttribute('data-asunto');
            document.getElementById('edit_subtarea_descripcion').value = this.getAttribute('data-descripcion');
            document.getElementById('edit_subtarea_prioridad').value = this.getAttribute('data-prioridad');
            document.getElementById('edit_subtarea_estado').value = this.getAttribute('data-estado');
            document.getElementById('edit_subtarea_fecha_vencimiento').value = this.getAttribute('data-fecha_vencimiento');
            document.getElementById('edit_subtarea_fecha_recordatorio').value = this.getAttribute('data-fecha_recordatorio');
            document.getElementById('edit_subtarea_responsable').value = this.getAttribute('data-id_responsable');
            document.getElementById('edit_subtarea_permisos_admin').checked = this.getAttribute('data-asignado_es_admin') === '1';
        });
    });

    // Manejo del modal de eliminación de subtarea
    const deleteSubtareaModal = new bootstrap.Modal(document.getElementById('confirmarEliminarSubtareaModal'));
    
    document.querySelectorAll('.delete-subtask-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('idEliminarSubtarea').value = this.getAttribute('data-id');
        });
    });

    // Manejo del cambio de estado
    document.querySelectorAll('.estado-select').forEach(select => {
        select.addEventListener('change', function(e) {
            e.preventDefault();
            
            const form = this.closest('form');
            const formData = new FormData(form);
            const select = this;
            
            select.disabled = true;
            const originalValue = select.value;
            const originalText = select.selectedOptions[0].text;
            select.selectedOptions[0].text = 'Actualizando...';
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'No se pudo actualizar'));
                    select.value = originalValue;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error de conexión');
                select.value = originalValue;
            })
            .finally(() => {
                select.disabled = false;
                select.selectedOptions[0].text = originalText;
            });
        });
    });

    // Manejo del modal de edición de tarea
    document.querySelectorAll('.edit-task').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('edit_task_id').value = this.getAttribute('data-id');
            document.getElementById('edit_asunto').value = this.getAttribute('data-asunto');
            document.getElementById('edit_descripcion').value = this.getAttribute('data-descripcion');
            document.getElementById('edit_prioridad').value = this.getAttribute('data-prioridad');
            document.getElementById('edit_fecha_vencimiento').value = this.getAttribute('data-fecha_vencimiento');
            
            const recordatorio = this.getAttribute('data-fecha_recordatorio');
            document.getElementById('edit_fecha_recordatorio').value = 
                recordatorio && recordatorio !== 'null' && recordatorio !== '' 
                ? recordatorio.split(' ')[0].split('T')[0] 
                : '';
        });
    });
});
</script>

