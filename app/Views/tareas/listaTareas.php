<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Tareas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .tarea { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .subtarea { margin-left: 20px; padding: 5px; background-color: #f5f5f5; border-left: 3px solid #999; margin-top: 10px; }
        h2 { margin: 0; }
    </style>
</head>
<body>

    <h1>Listado de Tareas</h1>

    <?php foreach ($tareas as $tarea): ?>
        <div class="tarea">
            <h2><?= esc($tarea['asunto']) ?></h2>
            <p><strong>Descripción:</strong> <?= esc($tarea['descripcion']) ?></p>
            <p><strong>Prioridad:</strong> <?= esc($tarea['prioridad']) ?> | <strong>Estado:</strong> <?= esc($tarea['estado']) ?></p>
            <p><strong>Vence:</strong> <?= esc($tarea['fecha_vencimiento']) ?></p>

            <?php if (!empty($tarea['subtareas'])): ?>
                <h4>Subtareas:</h4>
                <?php foreach ($tarea['subtareas'] as $sub): ?>
                    <div class="subtarea">
                        <p><strong><?= esc($sub['descripcion']) ?></strong></p>
                        <p>Estado: <?= esc($sub['estado']) ?> | Prioridad: <?= esc($sub['prioridad']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p><em>Sin subtareas</em></p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

</body>
</html>
