<?php $session = session(); ?>

<style>
  html, body {
  height: 100%;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
}

.navbar{
 border-bottom: 1px solid #dee2e6;
}

main {
  flex: 1;
}

</style>

<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?? 'Mi sitio' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
<header>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= base_url('/') ?>">TickTask</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      </ul>
    <?php if ($session->get('loggedIn')): ?>
    <div class="dropdown">
      <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <?= esc($session->get('username')) ?> 👤
      </button>
      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="<?= site_url('perfil') ?>">Perfil</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="<?= site_url('auth/logout') ?>">Cerrar sesión</a></li>
      </ul>
    </div>
    <?php else: ?>
      <?php 
      $currentUrl = current_url();
      $loginUrl = site_url('auth/login');
      $registerUrl = site_url('auth/registro');
      
      if ($currentUrl != $loginUrl && $currentUrl != $registerUrl): ?>
        <a href="<?= site_url('auth/login') ?>" class="btn btn-primary">Ingresar</a>
      <?php endif; ?>
    <?php endif; ?>
    </div>
  </div>
</nav>
</header>