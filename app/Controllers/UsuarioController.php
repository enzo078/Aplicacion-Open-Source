<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class UsuarioController extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    // Obtener todos los usuarios
    public function index()
    {
        $usuarios = $this->usuarioModel->findAll();
        return $this->respond($usuarios);
    }

    // Obtener un usuario por id
    public function show($id = null)
    {
        $usuario = $this->usuarioModel->find($id);
        if ($usuario) {
            return $this->respond($usuario);
        } else {
            return $this->failNotFound('No se encontro al usuario');
        }
    }

    // Crear nuevo usuario
    public function create()
    {
        $data = $this->request->getPost();
        
        // Hash de la contraseña
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        if ($this->usuarioModel->insert($data)) {
            return $this->respondCreated(['message' => 'Usuario creado']);
        }
        return $this->fail('Error al crear el usuario');
    }

    // Mostrar el formulario de registro
    public function registro()
    {
        return view('usuarios/registro');
    }

    // Procesar el registro
    public function guardar()
    {
        $data = $this->request->getPost();

        // Verificar que el email no esté registrado
        $usuarioExistente = $this->usuarioModel->where('email', $data['email'])->first();
        if ($usuarioExistente) {
            return redirect()->back()->with('error', 'El correo electrónico ya está registrado.');
        }

        // Hash de la contraseña
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        // Insertar usuario en la base de datos
        if ($this->usuarioModel->insert($data)) {
            return redirect()->to('/login')->with('message', 'Usuario registrado con éxito. Inicia sesión.');
        }

        return redirect()->back()->with('error', 'Hubo un problema al registrar el usuario.');
    }

    // Actualizar usuario
    public function update($id = null)
    {
        $data = $this->request->getRawInput();

        if ($this->usuarioModel->update($id, $data)) {
            return $this->respond(['message' => 'Usuario actualizado']);
        }
        return $this->fail('Error al actualizar el usuario');
    }

    // Eliminar usuario
    public function delete($id = null)
    {
        if ($this->usuarioModel->delete($id)) {
            return $this->respondDeleted(['message' => 'Usuario eliminado correctamente']);
        }
        return $this->fail('Error al eliminar el usuario');
    }
}
