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
$routes->get('auth/logout', 'AuthController::logout');
$routes->get('perfil/tareas', 'PerfilController::index');
$routes->get('tareas/crearTarea', 'TareaController::crearTarea');
$routes->post('tareas/create', 'TareaController::create');
$routes->get('subtareas/lista', 'SubTareaController::index'); 
$routes->get('subtareas/crear', 'SubTareaController::create'); 
$routes->post('subtarea/guardar', 'SubTareaController::store'); 
$routes->get('subtarea/editar/(:num)', 'SubTareaController::edit/$1');
$routes->post('subtarea/actualizar/(:num)', 'SubTareaController::update/$1'); 
$routes->get('subtarea/eliminar/(:num)', 'SubTareaController::delete/$1'); 
$routes->get('/perfil', 'PerfilController::perfil');
$routes->post('/usuarios/update/(:num)', 'UsuarioController::update/$1');
