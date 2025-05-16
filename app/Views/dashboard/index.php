<!DOCTYPE html>
<html lang="es">
<head>
    <title><?= $title ?? 'Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
        }
        .sidebar .nav-link {
            color: #333;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }
        .sidebar .nav-link:hover:not(.active) {
            background-color: #e9ecef;
        }
        .priority-label {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            color: black;
            white-space: nowrap;
        }

        .actions-container {
            display: flex;
            gap: 0.3rem;
        }
        .task-description {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .priority-high {
            background-color: #dc3545;
        }
        
        .priority-medium {
            background-color: #ffc107;
            color: #212529 !important;
        }
        
        .priority-low {
            background-color: #28a745;
        }
        
        .task-card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
                
        .card-footer {
            position: relative;
            z-index: 2; 
        }
        .task-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .task-description {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .status-badge {
            font-size: 0.8rem;
        }
        .subtask-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .subtask-checkbox {
            margin-right: 10px;
        }
        .subtask-title {
            flex-grow: 1;
        }
        .subtask-status {
            font-size: 0.8rem;
            padding: 2px 8px;
            border-radius: 10px;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
                border-right: none;
                border-bottom: 1px solid #dee2e6;
            }
        }
        
    </style>
</head>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<body>
    <?= view('layouts/header') ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 p-0 sidebar">
                <div class="p-3">
                    <h5 class="text-center mb-4">Menú</h5>
                    <nav class="nav flex-column">
                        <a href="<?= site_url('dashboard') ?>" class="nav-link <?= !isset($active_filter) ? 'active' : '' ?>">
                            <i class="fas fa-tasks me-2"></i>Tareas activas
                        </a>
                        <a href="<?= site_url('dashboard?filter=completed') ?>" class="nav-link <?= isset($active_filter) && $active_filter === 'completed' ? 'active' : '' ?>">
                            <i class="fas fa-check-circle me-2"></i>Completadas
                        </a>
                        <a href="<?= site_url('dashboard?filter=pending') ?>" class="nav-link <?= isset($active_filter) && $active_filter === 'pending' ? 'active' : '' ?>">
                            <i class="fas fa-clock me-2"></i>En proceso
                        </a>
                        <a href="<?= site_url('dashboard?filter=archived') ?>" class="nav-link <?= isset($active_filter) && $active_filter === 'archived' ? 'active' : '' ?>">
                            <i class="fas fa-archive me-2"></i>Archivadas
                        </a>
                        <a href="<?= site_url('dashboard?filter=subtareas') ?>" class="nav-link <?= isset($active_filter) && $active_filter === 'subtareas' ? 'active' : '' ?>">
                            <i class="fas fa-list-check me-2"></i>Mis Subtareas
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="col-md-9 col-lg-10 p-4">
                <?php if (isset($active_filter) && $active_filter === 'subtareas'): ?>
                    <!-- Vista específica para Mis Subtareas -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">
                            <i class="fas fa-list-check text-primary me-2"></i>Mis Subtareas
                        </h2>
                    </div>

                    <?php if (empty($subtareas)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>No tienes subtareas asignadas.
                        </div>
                    <?php else: ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="25%">Subtarea</th>
                                                <th width="25%">Tarea Principal</th>
                                                <th width="20%">Estado</th>
                                                <th width="25%">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($subtareas as $index => $subtarea): ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><?= esc($subtarea['asunto']) ?></td>
                                                    <td><?= esc($subtarea['tarea_asunto'] ?? 'Sin tarea') ?></td>
                                                    <td>
                                                        <span class="badge bg-<?= $subtarea['estado'] === 'Completada' ? 'success' : 'secondary' ?>">
                                                            <?= esc($subtarea['estado']) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <form action="<?= site_url('subtareas/cambiarEstado') ?>" method="post" class="d-inline">
                                                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                                                            <input type="hidden" name="id" value="<?= $subtarea['id'] ?>">
                                                            <input type="hidden" name="estado" value="<?= $subtarea['estado'] === 'Completada' ? 'En Proceso' : 'Completada' ?>">
                                                            <button type="submit" class="btn btn-sm <?= $subtarea['estado'] === 'Completada' ? 'btn-warning' : 'btn-success' ?>">
                                                                <?= $subtarea['estado'] === 'Completada' ? '<i class="fas fa-undo"></i> Reabrir' : '<i class="fas fa-check"></i> Completar' ?>
                                                            </button>
                                                        </form>
                                                        <a href="<?= site_url('tareas/ver/' . $subtarea['id'] . '/1') ?>" class="btn btn-sm btn-info ms-1" title="Ver tarea principal">
                                                            <i class="fas fa-eye"></i> Ver Tarea
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                
                <?php else: ?>
                    <!-- Vista normal de tareas -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">
                            <?= match($active_filter ?? '') {
                                'completed' => '<i class="fas fa-check-circle text-success me-2"></i>Tareas completadas',
                                'pending' => '<i class="fas fa-clock text-warning me-2"></i>Tareas en proceso',
                                'archived' => '<i class="fas fa-archive text-secondary me-2"></i>Tareas archivadas',
                                default => '<i class="fas fa-tasks text-primary me-2"></i>Tareas activas'
                            } ?>
                        </h2>
                        <div>
                            <a href="<?= site_url('tareas/crearTarea') ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Nueva Tarea
                            </a>
                        </div>
                    </div>

                    <?php if (empty($tareas)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>No hay tareas en esta categoría.
                        </div>
                    <?php else: ?>
                      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                            <?php foreach ($tareas as $tarea): ?>
                                <div class="col">
                                    <div class="card task-card h-100">
                                        <div class="card-body d-flex flex-column">
                                            <!-- Contenido superior -->
                                            <div>
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h5 class="card-title mb-0"><?= esc($tarea['asunto']) ?></h5>
                                                    <span class="badge status-badge bg-secondary">
                                                        <?= esc($tarea['estado']) ?>
                                                    </span>
                                                </div>
                                                
                                                <p class="card-text task-description mb-3">
                                                    <?= esc($tarea['descripcion']) ?>
                                                </p>
                                                
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <small class="text-muted">
                                                        <i class="far fa-calendar-alt me-1"></i>
                                                        <?= date('d/m/Y', strtotime($tarea['fecha_vencimiento'])) ?>
                                                    </small>
                                                </div>
                                                
                                                <?php if (!empty($tarea['subtareas'])): ?>
                                                    <div class="mb-3">
                                                        <h6 class="text-muted small mb-2">
                                                            <i class="fas fa-tasks me-1"></i>Subtareas (<?= count($tarea['subtareas']) ?>)
                                                        </h6>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Sección ABSOLUTAMENTE al final -->
                                            <div class="mt-auto pt-3 border-top">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <?php
                                                        $priorityClass = match(strtolower($tarea['prioridad'])) {
                                                            'alta' => 'priority-high',
                                                            'normal' => 'priority-medium',
                                                            'baja' => 'priority-low',
                                                            default => 'priority-low'
                                                        };
                                                    ?>
                                                    <span class="priority-label <?= $priorityClass ?>">
                                                        <i class="fas fa-<?= 
                                                            $priorityClass === 'priority-high' ? 'exclamation-triangle' : 
                                                            ($priorityClass === 'priority-medium' ? 'exclamation-circle' : 'check-circle')
                                                        ?> me-1"></i>
                                                        <?= esc($tarea['prioridad']) ?>
                                                    </span>
                                                    
                                                    <div class="d-flex">
                                                        <?php if ($tarea['archivada'] == 1): ?>
                                                            <a href="<?= site_url('tareas/ver/' . $tarea['id']) ?>" class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="<?= site_url('tareas/ver/' . $tarea['id']) ?>" class="btn btn-sm btn-outline-primary me-1" title="Ver detalles">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <form action="<?= base_url('tareas/eliminar') ?>" method="post" class="d-inline me-1">
                                                                <input type="hidden" name="id" value="<?= $tarea['id'] ?>">
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                            <form action="<?= site_url('tareas/archivar') ?>" method="post" class="d-inline">
                                                                <input type="hidden" name="id" value="<?= $tarea['id'] ?>">
                                                                <button type="submit" class="btn btn-sm btn-outline-dark" title="Archivar">
                                                                    <i class="fas fa-archive"></i>
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>