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

    //Obtener usuarios por email o por username
    public function findByEmailorUsername($data = null)
    {
        $usuario = null;
        
        if(filter_var($data, FILTER_VALIDATE_EMAIL)){
            $usuario = $this->usuarioModel->where('email', $data)->first();
        }
        else {
            $usuario = $this->usuarioModel->where('username', $data)->first();
        }

        if ($usuario){
            return $this->respond($usuario);
        } else {
            return $this->failNotFound('No se encontro un usuario con este correo o nombre de usuario');
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
