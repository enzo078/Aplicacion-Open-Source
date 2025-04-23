<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('tareas', 'TareaController::index');
$routes->get('usuario/registro', 'UsuarioController::registro');
$routes->post('usuario/registro', 'UsuarioController::guardar');