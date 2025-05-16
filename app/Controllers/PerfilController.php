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
        $this->usuarioModel = model('UsuarioModel');
        $this->tareaModel = model('TareaModel');
        helper(['form', 'url', 'session']);
   
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

    public function actualizar($id)
{
    $session = session();
    
    // Verificar si el usuario está logueado y autorizado
    if (!$session->get('loggedIn') || $session->get('id') != $id) {
        return redirect()->to('/login')->with('error', 'Acceso no autorizado');
    }

    // Verificar que el ID sea válido
    if (!is_numeric($id) || $id <= 0) {
        log_message('error', 'ID de usuario no válido: ' . $id);
        return redirect()->back()->with('error', 'ID de usuario no válido');
    }

    // Obtener el usuario actual
    $usuarioActual = $this->usuarioModel->find($id);
    if (!$usuarioActual) {
        return redirect()->to('/perfil')->with('error', 'Usuario no encontrado');
    }

    // Reglas de validación
    $rules = [
        'nombre' => 'required|min_length[3]|max_length[100]',
        'username' => "required|min_length[3]|max_length[20]|is_unique[usuarios.username,id,{$id}]"
    ];

    // Validar el correo solo si ha cambiado
    $nuevoEmail = $this->request->getPost('email');
    if ($nuevoEmail != $usuarioActual['email']) {
        $rules['email'] = 'required|valid_email|is_unique[usuarios.email]';
    }

    // Validar la contraseña solo si se ha ingresado
    if (!empty($this->request->getPost('password'))) {
        $rules['password'] = 'min_length[8]';
        $rules['confirm_password'] = 'required|matches[password]';
    }

    // Validar los datos del formulario
    if (!$this->validate($rules)) {
        log_message('error', 'Errores de validación: ' . json_encode($this->validator->getErrors()));
        return redirect()->back()
                       ->withInput()
                       ->with('errors', $this->validator->getErrors());
    }

    // Preparar los datos para la actualización
    $data = [
        'nombre' => $this->request->getPost('nombre'),
        'username' => $this->request->getPost('username'),
        'email' => $nuevoEmail
    ];

    // Actualizar la contraseña si se proporcionó
    if (!empty($this->request->getPost('password'))) {
        $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);
    }

    // Log de los datos antes de actualizar
    log_message('debug', 'Datos antes de actualizar: ' . json_encode($data));

    // Verificar si hay cambios en los datos
    $hayCambios = false;
    foreach ($data as $key => $value) {
        if ($usuarioActual[$key] !== $value) {
            $hayCambios = true;
            break;
        }
    }

    if (!$hayCambios) {
        log_message('info', 'No se detectaron cambios en el perfil.');
        return redirect()->to('/perfil')->with('info', 'No se detectaron cambios para guardar');
    }

    try {
        // Conectar a la base de datos manualmente
        $db = \Config\Database::connect();
        $builder = $db->table('usuarios');

        // Actualización manual
        if ($builder->update($data, ['id' => $id])) {
            // Actualizar la sesión con los nuevos datos
            $session->set([
                'nombre' => $data['nombre'],
                'username' => $data['username'],
                'email' => $data['email']
            ]);

            // Verificar el resultado después de la actualización
            $usuarioActualizado = $builder->getWhere(['id' => $id])->getRowArray();
            log_message('debug', 'Datos después de actualizar: ' . json_encode($usuarioActualizado));

            log_message('info', 'Perfil actualizado correctamente.');
            return redirect()->to('/perfil')->with('success', 'Perfil actualizado correctamente');
        }

        // Error en la actualización
        log_message('error', 'No se pudo completar la actualización manual.');
        throw new \RuntimeException('No se pudo completar la actualización');
        
    } catch (\Exception $e) {
        // Capturar cualquier excepción
        log_message('error', 'Error manual al actualizar perfil: ' . $e->getMessage());
        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Error al actualizar perfil: ' . $e->getMessage());
    }
}

}

