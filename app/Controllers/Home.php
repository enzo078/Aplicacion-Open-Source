<?php namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Si el usuario está logueado, redirige al dashboard
        if (session()->get('loggedIn')) {
            return redirect()->to('/dashboard');
        }

        // Vista para visitantes no logueados
        return view('index', [
            'title' => 'TickTask - Organiza tus tareas fácilmente'
        ]);
    }
}