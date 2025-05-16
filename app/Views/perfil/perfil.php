<?= view('layouts/header') ?>
<?php $session = session(); ?>

<style>
    .profile-container {
        max-width: 500px;
        margin: 2rem auto;
        padding: 2rem;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }
    
    .profile-title {
        text-align: center;
        color: #333;
        margin-bottom: 1.5rem;
    }
    
    .form-group {
        margin-bottom: 1.2rem;
    }
    
    label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #555;
    }
    
    input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
        transition: border 0.3s;
    }
    
    input:focus {
        border-color: #5867dd;
        outline: none;
        box-shadow: 0 0 0 2px rgba(88, 103, 221, 0.2);
    }
    
    .btn-primary {
        background-color: #5867dd;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
        transition: background-color 0.3s;
    }
    
    .btn-primary:hover {
        background-color: #4755b5;
    }
    
    .password-toggle {
        position: relative;
    }
    
    .password-toggle-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
    }
    
    .alert {
        padding: 10px;
        margin-bottom: 1rem;
        border-radius: 5px;
    }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }
</style>

<?php if ($session->get('loggedIn')): ?>
<div class="profile-container">
    <h2 class="profile-title">Mi Perfil</h2>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form action="<?= site_url('perfil/actualizar/' . ($usuario['id'] ?? '')) ?>" method="post">
        <div class="form-group">
            <label for="nombre">Nombre Completo</label>
            <input type="text" name="nombre" id="nombre" 
                   value="<?= old('nombre', esc($usuario['nombre'] ?? '')) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="username">Nombre de Usuario</label>
            <input type="text" name="username" id="username" 
                   value="<?= old('username', esc($usuario['username'] ?? '')) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" name="email" id="email" 
                   value="<?= old('email', esc($usuario['email'] ?? '')) ?>" required>
        </div>
        
        <div class="form-group password-toggle">
            <label for="password">Cambiar Contraseña (dejar en blanco para no cambiar)</label>
            <input type="password" name="password" id="password" placeholder="Nueva contraseña"
                   value="<?= old('password') ?>">
            <span class="password-toggle-icon" onclick="togglePassword('password')">👁️</span>
        </div>
        
        <div class="form-group password-toggle">
            <label for="confirm_password">Confirmar Nueva Contraseña</label>
            <input type="password" name="confirm_password" id="confirm_password" 
                   placeholder="Confirmar nueva contraseña"
                   value="<?= old('confirm_password') ?>">
            <span class="password-toggle-icon" onclick="togglePassword('confirm_password')">👁️</span>
        </div>
        
        <button type="submit" class="btn-primary">Actualizar Perfil</button>
    </form>
</div>

<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        field.type = field.type === 'password' ? 'text' : 'password';
    }
</script>
<?php endif; ?>