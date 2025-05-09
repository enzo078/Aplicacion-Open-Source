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
            position: absolute;
            bottom: 15px;
            left: 15px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #212529;
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
            position: relative; /* Necesario para posicionamiento absoluto de la etiqueta */
            padding-bottom: 50px; /* Espacio para la etiqueta */
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
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
                border-right: none;
                border-bottom: 1px solid #dee2e6;
            }
        }
    </style>
</head>
<body>
    <?= view('layouts/header') ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 p-0 sidebar">
                <div class="p-3">
                    <h5 class="text-center mb-4">Filtrar Tareas</h5>
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
                    </nav>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="col-md-9 col-lg-10 p-4">
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
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-0"><?= esc($tarea['asunto']) ?></h5>
                        <span class="badge status-badge bg-<?= 
                            $tarea['estado'] === 'Completada' ? 'success' : 
                            ($tarea['estado'] === 'En Proceso' ? 'warning' : 'info')
                        ?>">
                            <?= esc($tarea['estado']) ?>
                        </span>
                    </div>
                    
                    <p class="card-text task-description mb-3">
                        <?= esc($tarea['descripcion']) ?>
                    </p>
                    
                   <!-- Etiqueta de prioridad POSICIONADA -->
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
                            <ul class="list-group list-group-flush small">
                                <?php foreach ($tarea['subtareas'] as $subtarea): ?>
                                    <li class="list-group-item py-2 d-flex justify-content-between align-items-center">
                                        <?= esc($subtarea['descripcion']) ?>
                                        <span class="badge bg-<?= 
                                            $subtarea['estado'] === 'Completada' ? 'success' : 
                                            ($subtarea['estado'] === 'En Proceso' ? 'warning' : 'secondary')
                                        ?>">
                                            <?= esc($subtarea['estado']) ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-transparent border-top-0">
                <div class="d-flex justify-content-end">
                    <?php if ($tarea['archivada'] == 1): ?>
                        <a href="<?= site_url('tareas/ver/' . $tarea['id']) ?>" class="btn btn-sm btn-outline-primary me-2" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </a>
                    <?php else: ?>
                        <a href="<?= site_url('tareas/ver/' . $tarea['id']) ?>" class="btn btn-sm btn-outline-primary me-2" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="<?= base_url('tareas/eliminar') ?>" method="post">
                            <input type="hidden" name="id" value="<?= $tarea['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        <form action="<?= base_url('tareas/archivar') ?>" method="post">
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
    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tooltips para los botones
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>