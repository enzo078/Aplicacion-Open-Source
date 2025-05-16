<?php $session = session(); ?>

<style>
  html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    overflow-x: hidden; 
  }

  .navbar {
    border-bottom: 1px solid #dee2e6;
    position: relative; 
  }

  main {
    flex: 1;
  }

  .dropdown-menu-end.show-volver {
    display: block !important;
    position: absolute; 
    right: 0; 
    left: auto; 
    white-space: nowrap; 
    max-width: 100vw; 
  }

  .dropdown {
    position: static; 
  }

  @media (max-width: 576px) {
    .dropdown-menu-end.show-volver {
      position: fixed; 
      top: auto;
      right: 10px; 
      transform: none; 
    }
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
          <button id="userDropdown" class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?= esc($session->get('username')) ?> 👤
          </button>
          <ul class="dropdown-menu dropdown-menu-end" id="userDropdownMenu">
            <?php if (uri_string() != 'perfil'): ?>
              <li><a class="dropdown-item" href="<?= site_url('perfil') ?>">Perfil</a></li>
              <li><hr class="dropdown-divider"></li>
            <?php else: ?>
              <li><a class="dropdown-item" href="<?= site_url('/') ?>">Volver</a></li>
              <li><hr class="dropdown-divider"></li>
            <?php endif; ?>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const userDropdown = document.getElementById('userDropdown');
    const dropdownMenu = document.getElementById('userDropdownMenu');

    userDropdown.addEventListener('click', function() {
      dropdownMenu.classList.toggle('show-volver');
    });
  });
</script>

</body>
</html>