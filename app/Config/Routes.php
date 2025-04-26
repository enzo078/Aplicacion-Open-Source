<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('tareas', 'TareaController::index');
$routes->get('auth/registro', 'AuthController::registro');
$routes->post('auth/registro', 'AuthController::guardar');
$routes->get('auth/login', 'AuthController::mostrarLogin');
$routes->post('auth/login', 'AuthController::login');