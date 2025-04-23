<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-container { width: 300px; margin: 0 auto; }
        label { font-weight: bold; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { width: 50%; padding: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;}
        button:hover { background-color: #45a049; }
    </style>
</head>
<body>

    <h1>Registro de Usuario</h1>

    <div class="form-container">
        <form action="<?= site_url('usuario/registro') ?>" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Registrar</button>

            <?php if (session()->getFlashdata('error')): ?>
                <div style="color: red;"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('message')): ?>
                <div style="color: green;"><?= session()->getFlashdata('message') ?></div>
            <?php endif; ?>

        </form>
    </div>

</body>
</html>