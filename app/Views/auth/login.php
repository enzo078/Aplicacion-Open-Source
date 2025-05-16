<?= view('layouts/header') ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h2 class="card-title text-center mb-4">Iniciar Sesión</h2>
                    
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('/auth/login') ?>" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo o Nombre de Usuario</label>
                            <input type="text" class="form-control" name="email" id="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" name="password" id="password" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <p>¿No tienes una cuenta? <a href="<?= site_url('auth/registro') ?>">Registrate acá</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
