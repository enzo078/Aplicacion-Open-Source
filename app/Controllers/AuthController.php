<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Controllers\BaseController;

class AuthController extends BaseController
{

    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel(); 
    }

    public function registro()
    {
        return view('auth/registro');
    }

   public function guardar()
{
    if (!$this->request->is('post')) {
        return redirect()->to('/auth/registro');
    }

    $data = $this->request->getPost();
    $data['es_admin'] = 0;

    $rules = [
        'nombre' => [
            'rules' => 'required|min_length[3]|max_length[100]',
            'errors' => [
                'required' => 'El nombre completo es obligatorio',
                'min_length' => 'El nombre debe tener al menos 3 caracteres',
                'max_length' => 'El nombre no puede exceder los 100 caracteres'
            ]
        ],
        'username' => [
            'rules' => 'required|min_length[3]|max_length[20]|is_unique[usuarios.username]',
            'errors' => [
                'required' => 'El nombre de usuario es obligatorio',
                'min_length' => 'El usuario debe tener al menos 3 caracteres',
                'max_length' => 'El usuario no puede exceder los 20 caracteres',
                'is_unique' => 'Este nombre de usuario ya está en uso'
            ]
        ],
        'email' => [
            'rules' => 'required|valid_email|is_unique[usuarios.email]',
            'errors' => [
                'required' => 'El correo electrónico es obligatorio',
                'valid_email' => 'Debe ingresar un correo electrónico válido',
                'is_unique' => 'Este correo ya está registrado'
            ]
        ],
        'password' => [
            'rules' => 'required|min_length[8]',
            'errors' => [
                'required' => 'La contraseña es obligatoria',
                'min_length' => 'La contraseña debe tener al menos 8 caracteres'
            ]
        ]
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $userData = [
        'nombre' => $data['nombre'],
        'username' => $data['username'],
        'email' => $data['email'],
        'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        'rol' => 'usuario',
        'es_admin' => 0
    ];

    try {
        $id = $this->usuarioModel->insert($userData);
        
        if (!$id) {
            throw new \RuntimeException('No se pudo obtener el ID del usuario registrado');
        }

        return redirect()->to('/auth/login')->with('message', '¡Registro exitoso! Por favor inicia sesión.');

    } catch (\Exception $e) {
        log_message('error', 'Error en registro: ' . $e->getMessage());
        return redirect()->back()
            ->withInput()
            ->with('error', 'Ocurrió un error al registrar. Por favor inténtalo nuevamente.');
    }
}

    public function mostrarLogin()
    {
        return view('auth/login');
    }

    public function login()
    {
        $data = $this->request->getPost();

    $usuario = $this->usuarioModel->where('email', $data['email'])
                        ->orWhere('username', $data['email'])
                        ->first();

    if (!$usuario) {
        return redirect()->to('/auth/login')->with('error', 'Credenciales incorrectas');
    }

    log_message('debug', 'Stored hash: '.$usuario['password']);
    log_message('debug', 'Input password: '.$data['password']);

    if (!password_verify($data['password'], $usuario['password'])) {
        log_message('debug', 'Password verification failed');
        return redirect()->to('/auth/login')->with('error', 'Contraseña incorrecta');
    }
        // Iniciar sesión
        $session = session();
        $session->set('id', $usuario['id']);
        $session->set('nombre', $usuario['nombre']);
        $session->set('username', $usuario['username']);
        $session->set('email', $usuario['email']);
        $session->set('rol', $usuario['rol']);
        $session->set('loggedIn', true);
        return redirect()->to('/')->with('message', 'Bienvenido, ' . $usuario['nombre'] . '!');
    }


    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/')->with('message', 'Has cerrado sesión con éxito.');
    }


}

