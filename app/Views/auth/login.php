<?= view('layouts/header') ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<form action="<?= site_url('/auth/login') ?>" method="post">
    <div>
        <label for="email">Correo o Nombre de Usuario</label>
        <input type="text" name="email" id="email" required>
    </div>

    <div>
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" required>
    </div>

    <button type="submit">Iniciar Sesión</button>

</form>

