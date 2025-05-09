<?= view('layouts/header') ?>
<?php $session = session(); ?>

<style>
        form {
            max-width: 400px;
            margin: auto;
            padding: 1rem;
            background: #f7f7f7;
            border-radius: 10px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        button {
            margin-top: 15px;
            padding: 10px;
            width: 100%;
            background-color: #5867dd;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
<?php if ($session->get('loggedIn')): ?>
    <h2 style="text-align:center;">Mi Perfil</h2>
    <form action="<?= site_url('usuario/update' . $usuario['id']) ?>" method="post">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" value="<?= esc($usuario['nombre']) ?>" required>

        <label for="username">Nombre de Usuario</label>
        <input type="text" name="username" id="username" value="<?= esc($usuario['username']) ?>" required>

        <button type="submit">Actualizar Perfil</button>
    </form>
<?php endif; ?>
