<?= view('layouts/header') ?>

<?= $this->section('content') ?>
<h1>Lista de Subtareas</h1>

<?php if (!empty($subtareas)): ?>
    <ul>
        <?php foreach ($subtareas as $subtarea): ?>
            <li>
                <strong><?= esc($subtarea['descripcion']) ?></strong> - <?= esc($subtarea['estado']) ?>
                <a href="<?= site_url('subtareas/edit/' . $subtarea['id']) ?>">Editar</a>
                <a href="<?= site_url('subtareas/delete/' . $subtarea['id']) ?>" onclick="return confirm('¿Estás seguro de eliminar esta subtarea?')">Eliminar</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No hay subtareas disponibles.</p>
<?php endif; ?>

<a href="<?= site_url('subtareas/create') ?>">Crear Subtarea</a>

<?= view('layouts/footer') ?>
