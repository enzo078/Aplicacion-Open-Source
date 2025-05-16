<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('', function($routes) {
    
    // Rutas Públicas
    $routes->get('/', 'Home::index');
    
    // Autenticación
    $routes->group('auth', function($routes) {
        $routes->get('registro', 'AuthController::registro');
        $routes->post('registro', 'AuthController::guardar');
        $routes->get('login', 'AuthController::mostrarLogin');
        $routes->post('login', 'AuthController::login');
        $routes->get('logout', 'AuthController::logout');
    });
    
    // Dashboard
    $routes->group('dashboard', function($routes) {
        $routes->get('/', 'DashboardController::index');
        $routes->get('tareas/editar/(:num)', 'DashboardController::editarTarea/$1');
    });
    
    // Perfil de Usuario
    $routes->group('perfil', function($routes) {
        $routes->get('/', 'PerfilController::perfil');
        $routes->get('tareas', 'PerfilController::index');
    });
    
    // Gestión de Tareas
    $routes->group('tareas', function($routes) {
        $routes->get('/', 'TareaController::index');
        $routes->get('crearTarea', 'TareaController::crearTarea');
        $routes->post('create', 'TareaController::create');
        $routes->post('eliminar', 'TareaController::eliminar');
        $routes->post('archivar', 'TareaController::archivar');
        $routes->get('ver/(:num)', 'TareaController::ver/$1');
        $routes->get('ver/(:num)(/:any)', 'TareaController::ver/$1/$2');
        $routes->post('actualizar/(:num)', 'TareaController::update/$1');
    });
    
    // Gestión de Subtareas
    $routes->group('subtareas', function($routes) {
        $routes->post('crear', 'SubTareaController::crear');
        $routes->get('editar/(:num)', 'SubTareaController::edit/$1');
        $routes->post('actualizar/(:num)', 'SubTareaController::update/$1');
        $routes->post('cambiarEstado', 'SubTareaController::cambiarEstado');
        $routes->post('eliminar', 'SubTareaController::eliminar');
    });
    
    // Gestión de Usuarios
    $routes->group('usuarios', function($routes) {
        $routes->post('update/(:num)', 'UsuarioController::update/$1');
    });

});


