<?php $session = session(); ?>

<?= view('layouts/header') ?>
<style>
    /*body { font-family: Arial, sans-serif; margin: 20px; }*/
    .tarea { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
    .subtarea { margin-left: 20px; padding: 5px; background-color: #f5f5f5; border-left: 3px solid #999; margin-top: 10px; }
    h2 { margin: 0; }
</style>
<main>
<h1>Listado de Tareas</h1>
<?php if ($session->get('logged_in')): ?>
    <p>Bienvenido, <?= esc($session->get('username')) ?> <br>Estas son tus tareas</p>
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
    <?php else: ?>
        <p>Por favor, inicia sesión para ver tus tareas.</p>
        <a href="<?= site_url('auth/login') ?>" class="btn btn-primary">Iniciar sesión</a>
    <?php endif; ?>

</main>
<?= view('layouts/footer')?>

