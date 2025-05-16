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
            // Solo mostrar botón de edición si es el creador o admin
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
                    <!-- Campos del formulario -->
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
        <?php 
        // Solo mostrar botón de nueva subtarea si es creador o admin
        if ($esCreador || $esAdmin): ?>
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
                        // Determinar permisos para esta subtarea específica
                        $puedeEditarSubtarea = false;
                        if ($esCreador || $esAdmin) {
                            $puedeEditarSubtarea = true;
                        } elseif ($subtarea['id_responsable'] == session()->get('id') && ($subtarea['asignado_es_admin'] ?? 0)) {
                            $puedeEditarSubtarea = true;
                        }
                        if ($esCreador || $esAdmin): ?>
                            <button class="btn btn-sm btn-outline-secondary edit-subtask" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editarSubtareaModal"
                                data-id="<?= $subtarea['id'] ?>"
                                data-asunto="<?= esc($subtarea['asunto']) ?>"
                                data-descripcion="<?= esc($subtarea['descripcion']) ?>"
                                data-prioridad="<?= esc($subtarea['prioridad']) ?>"
                                data-responsable="<?= $subtarea['id_responsable'] ?? '' ?>"
                                data-admin="<?= $subtarea['asignado_es_admin'] ?? 0 ?>">
                                <i class="fas fa-edit" title="Editar"></i>
                            </button>
                        <?php endif; ?>
                        
                        <?php 
                        if ($esCreador || $esAdmin): ?>
                            <button class="btn btn-sm btn-outline-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#confirmarEliminarModal"
                                onclick="document.getElementById('subtareaIdEliminar').value = <?= $subtarea['id'] ?>">
                                <i class="fas fa-trash-alt" title="Eliminar"></i>
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

<!-- Modal para editar subtarea-->
<div class="modal fade" id="editarSubtareaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('subtareas/actualizar') ?>" method="post">
                <input type="hidden" name="id" id="edit_subtask_id">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Subtarea</h5>
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
                            <option value="Baja">Baja</option>
                            <option value="Normal">Normal</option>
                            <option value="Alta">Alta</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha Vencimiento</label>
                        <input type="date" name="fecha_vencimiento" id="edit_fecha_vencimiento" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Asignar a</label>
                        <select name="id_responsable" id="edit_id_responsable" class="form-select">
                            <option value="">Seleccionar colaborador</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?= $usuario['id'] ?>">
                                    <?= esc($usuario['username']) ?> (<?= esc($usuario['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="asignado_es_admin" id="edit_permisosAdmin" value="1">
                        <label class="form-check-label" for="edit_permisosAdmin">
                            Permitir que este colaborador edite la subtarea
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="confirmarEliminarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar esta subtarea? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formEliminarSubtarea" method="post" action="<?= site_url('subtareas/eliminar') ?>">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <input type="hidden" name="id" id="subtareaIdEliminar" />
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>


<!-- Script para el modal de edición de subtarea -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.edit-subtask').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = {
                id: this.getAttribute('data-id'),
                asunto: this.getAttribute('data-asunto'),
                descripcion: this.getAttribute('data-descripcion'),
                prioridad: this.getAttribute('data-prioridad'),
                responsable: this.getAttribute('data-responsable'),
                admin: this.getAttribute('data-admin')
            };
            
            document.getElementById('edit_subtask_id').value = data.id;
            document.getElementById('edit_asunto').value = data.asunto;
            document.getElementById('edit_descripcion').value = data.descripcion;
            document.getElementById('edit_prioridad').value = data.prioridad;
            document.getElementById('edit_id_responsable').value = data.responsable;
            document.getElementById('edit_permisosAdmin').checked = data.admin == 1;
            
            updatePriorityPreview('edit_prioridad', 'editarSubtareaModal');
        });
    });
    
    function updatePriorityPreview(selectId, modalId) {
        const select = document.getElementById(selectId);
        const preview = document.querySelector(`#${modalId} .priority-preview .priority-label`);
        
        if (!select || !preview) return;
        
        const priority = select.value.toLowerCase();
        preview.className = 'priority-label priority-' + priority;
        
        const icon = preview.querySelector('i');
        if (icon) {
            icon.className = 'fas fa-' + 
                (priority === 'alta' ? 'exclamation-triangle' :
                 priority === 'normal' ? 'exclamation-circle' : 'check-circle') + ' me-1';
        }
    }
});
</script>

<!-- Script para el modal de edición de tarea -->
<script>
document.querySelectorAll('.edit-task').forEach(btn => {
    btn.addEventListener('click', function() {
        const setValueIfExists = (elementId, value) => {
            const element = document.getElementById(elementId);
            if (element) element.value = value;
        };

        setValueIfExists('edit_task_id', this.getAttribute('data-id'));
        setValueIfExists('edit_asunto', this.getAttribute('data-asunto'));
        setValueIfExists('edit_descripcion', this.getAttribute('data-descripcion'));
        setValueIfExists('edit_prioridad', this.getAttribute('data-prioridad'));
        setValueIfExists('edit_fecha_vencimiento', this.getAttribute('data-fecha_vencimiento'));
        
        const recordatorio = this.getAttribute('data-fecha_recordatorio');
        const fechaRecordatorioElement = document.getElementById('edit_fecha_recordatorio');
        if (fechaRecordatorioElement) {
            fechaRecordatorioElement.value = recordatorio && recordatorio !== 'null' && recordatorio !== '' 
                ? recordatorio.split(' ')[0].split('T')[0] 
                : '';
            
            console.log('Fecha procesada:', fechaRecordatorioElement.value);
        }

    });
});
</script>

<!-- Script para manejar el cambio de estado -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Interceptar todos los selects de estado
    document.querySelectorAll('.estado-select').forEach(select => {
        select.addEventListener('change', function(e) {
            e.preventDefault(); // Evitar envío normal del formulario
            
            const form = this.closest('form');
            const formData = new FormData(form);
            const select = this;
            
            // Mostrar carga
            select.disabled = true;
            const originalValue = select.value;
            const originalText = select.selectedOptions[0].text;
            select.selectedOptions[0].text = 'Actualizando...';
            
            // Enviar por AJAX
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Identificar como AJAX
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Recargar solo la página para ver cambios
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
});
</script>


</body>