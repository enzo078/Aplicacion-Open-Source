<?php
namespace App\Controllers;

use App\Models\TareaModel;
use App\Models\UsuarioModel;

class PerfilController extends BaseController
{
    protected $usuarioModel;
    protected $tareaModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel(); // Inicializa el modelo de usuario
        $this->tareaModel = new TareaModel();    // Inicializa el modelo de tareas
    }

    public function index()
    {
        $session = session();
        $tareas = $this->tareaModel->getTareasByUserId($session->get('id'));
        return view('perfil/tareas', ['tareas' => $tareas]);
    }

    public function perfil()
    {
        $session = session();
        $id = $session->get('id');

        if (!$id) {
            return redirect()->to('auth/login'); 
        }

        $usuario = $this->usuarioModel->find($id); 

        if (!$usuario) {
            return redirect()->to('auth/login')->with('error', 'Usuario no encontrado');
        }

        return view('perfil/perfil', [
            'usuario' => $usuario,
            'session' => $session
        ]);
    }
}