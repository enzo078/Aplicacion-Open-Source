<!DOCTYPE html>
<html>
<head>
    <title>Nueva asignación de subtarea</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; }
        .header { background-color: #f8f9fa; padding: 10px; text-align: center; }
        .content { padding: 20px; }
        .button { 
            display: inline-block; padding: 10px 20px; 
            background-color: #0d6efd; color: white; 
            text-decoration: none; border-radius: 5px; 
        }
        .footer { margin-top: 20px; font-size: 0.9em; color: #6c757d; }
        .priority {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
            color: white;
        }
        .priority-alta { background-color: #dc3545; } /* Rojo */
        .priority-normal { background-color: #ffc107; color: #000; } /* Amarillo */
        .priority-baja { background-color: #28a745; } /* Verde */
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Nueva asignación de subtarea</h2>
        </div>
        
        <div class="content">
            <p>Hola <?= esc($nombre) ?>,</p>
            
            <p>Has sido asignado como responsable de una nueva subtarea con los siguientes detalles:</p>
            
            <p><strong>Subtarea:</strong> <?= esc($asuntoSubtarea) ?></p>
            <p><strong>Tarea principal:</strong> #<?= $idTarea ?></p>
            
            <p><strong>Prioridad:</strong> 
                <span class="priority priority-<?= strtolower($prioridad) ?>">
                    <?= $prioridad ?>
                </span>
            </p>
            
            <?php if ($fechaVencimiento): ?>
            <p><strong>Fecha de vencimiento:</strong> <?= date('d/m/Y', strtotime($fechaVencimiento)) ?></p>
            <?php endif; ?>
            
            <p><strong>Permisos:</strong> <?= $tienePermisosAdmin ? 'Puedes editar esta subtarea' : 'Solo visualización' ?></p>
            
            <p style="margin-top: 30px;">
                <a href="<?= $base_url ?>/tareas/ver/<?= $idTarea ?>" class="button">
                    Ver la tarea en el sistema
                </a>
            </p>
        </div>
        
        <div class="footer">
            <p>Este es un mensaje automático, por favor no respondas directamente a este correo.</p>
        </div>
    </div>
</body>
</html>