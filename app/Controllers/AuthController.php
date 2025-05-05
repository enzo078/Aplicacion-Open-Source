<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Controllers\BaseController;

class AuthController extends BaseController
{

    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel(); // Cargar el modelo
    }

    // Mostrar el formulario de registro
    public function registro()
    {
        return view('auth/registro');
    }

    // Procesar el registro
    public function guardar()
    {
        $data = $this->request->getPost();

        // Verificar que el email no esté registrado
        $emailExistente = $this->usuarioModel->where('email', $data['email'])->first();
        if ($emailExistente) {
            return redirect()->to('/auth/registro')->with('error', 'El correo electrónico ya está registrado.');
        }
        
        // Verificar que el usuario no este registrado
        $userNameExistente = $this->usuarioModel->where('username', $data['username'])->first();
        if ($userNameExistente) {
            return redirect()->to('/auth/registro')->with('error', 'El nombre de usuario ya está  registrado');
        }
        
        // Hash de la contraseña
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        // Insertar usuario en la base de datos
        if ($this->usuarioModel->insert($data)) {
            return redirect()->to('auth/login')->with('message', 'Usuario registrado con éxito. Inicia sesión.');
        }

        return redirect()->to('/auth/registro')->with('error', 'Hubo un problema al registrar el usuario.');
    }

    public function mostrarLogin()
    {
        return view('auth/login');
    }

    public function login()
    {
        $data = $this->request->getPost();

        // Verificar si el usuario existe
        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $usuario = $this->usuarioModel->where('email', $data['email'])->first();
        } else {
            $usuario = $this->usuarioModel->where('username', $data['email'])->first();
        }
        if (!$usuario) {
            return redirect()->to('/auth/login')->with('error', 'El correo electrónico o nombre de usuario no está registrado.');
        }
        // Verificar la contraseña
        if (!password_verify($data['password'], $usuario['password'])) {
            return redirect()->to('/auth/login')->with('error', 'Contraseña incorrecta.');
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
