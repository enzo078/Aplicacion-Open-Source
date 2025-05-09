<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TareaModel;
use App\Models\SubtareaModel;

class TareaController extends BaseController
{
    protected $tareaModel;

    public function __construct()
    {
        $this->tareaModel = new TareaModel(); 
        $this->subTareaModel = new SubTareaModel();
    }

    public function index()
    {
        $userId = session()->get('id'); 
        if (!$userId) {
            return redirect()->to('/auth/login');
        }

        $tareas = $this->tareaModel->where('id_usuario', $userId)->findAll();
        return view('tareas/listaTareas', ['tareas' => $tareas]);
    }

    public function crearTarea()
    {
        return view('tareas/crearTarea');
    }

    public function create()
{
    if (!session()->get('loggedIn')) {
        return redirect()->to('/auth/login');
    }

    $data = $this->request->getPost();
    $userId = session()->get('id');

    $usuarioModel = new \App\Models\UsuarioModel();
    if (!$usuarioModel->find($userId)) {
        return redirect()->back()->with('error', 'Usuario no registrado');
    }

    $data['id_usuario'] = $userId;
    $data['estado'] = 'Pendiente'; 

    $rules = [
        'asunto' => 'required|min_length[3]',
        'id_usuario' => 'required|is_not_unique[usuarios.id]' 
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    if ($this->tareaModel->insert($data)) {
        return redirect()->to('/dashboard')->with('success', 'Tarea creada');
    } else {
        return redirect()->back()->withInput()->with('error', 'Error al guardar');
    }
}

    public function show($id = null)
    {
        $tarea = $this->tareaModel->find($id);
        if (!$tarea) {
            return $this->failNotFound('Tarea no encontrada');
        }
        return $this->respond($tarea);
    }


    public function editar($id)
    {
        $tarea = $this->tareaModel->where('id', $id)
                                ->where('id_usuario', session()->get('id'))
                                ->first();

        if (!$tarea && !session()->get('es_admin')) {
            return redirect()->to('/tareas')->with('error', 'Tarea no encontrada o no tienes permiso.');
        }
        return view('tareas/editarTarea', [
            'tarea' => $tarea,
            'prioridades' => ['Baja', 'Normal', 'Alta'] 
        ]);
    }

    public function actualizar($id)
    {
        $tarea = $this->tareaModel->where('id', $id)
                                ->where('id_usuario', session()->get('id'))
                                ->first();

        if (!$tarea) {
            return redirect()->to('/dashboard')->with('error', 'No tienes permiso');
        }

        $rules = [
            'asunto' => 'required|min_length[3]|max_length[150]',
            'prioridad' => 'required|in_list[Baja,Normal,Alta]',
            'fecha_vencimiento' => 'permit_empty|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $this->tareaModel->update($id, $this->request->getPost());
            return redirect()->to('/dashboard')->with('success', 'Tarea actualizada');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function eliminar()
{
    $db = \Config\Database::connect();
    $db->transStart();

    try {
        $id = $this->request->getPost('id');
        
        if (empty($id)) {
            throw new \Exception('ID de tarea no proporcionado');
        }

        $tarea = $this->tareaModel->where('id', $id)
                                ->where('id_usuario', session()->get('id'))
                                ->first();

        if (!$tarea && !session()->get('es_admin')) {
            throw new \Exception('No tienes permiso para esta acción');
        }

        $subtareas = $this->subTareaModel->where('id_tarea', $id)->findAll();
        
        foreach ($subtareas as $subtarea) {
            if ($subtarea['estado'] != 'completada') {
                throw new \Exception('No se puede eliminar: existen subtareas pendientes');
            }
        }

        $this->subTareaModel->where('id_tarea', $id)->delete(); 
        $this->tareaModel->delete($id);
        
        $db->transComplete();

        return redirect()->to('/dashboard')->with('success', 'Tarea y subtareas eliminadas correctamente');

    } catch (\Exception $e) {
        $db->transRollback();
        return redirect()->to('/dashboard')->with('error', $e->getMessage());
    }
}

    public function archivar()
    {
        $id = $this->request->getPost('id'); 
    
        $tarea = $this->tareaModel->where('id', $id)
                                ->where('id_usuario', session()->get('id'))
                                ->first();
        if (!$tarea) {
            return redirect()->to('/dashboard')->with('error', 'No tienes permiso');
        }

        $this->tareaModel->update($id, [
            'estado' => 'archivada',
            'archivada' => 1 
        ]);

        return redirect()->to('/dashboard?filter=archived')->with('success', 'Tarea archivada');
    }


    public function ver($id)
{
    $tareaModel = model('TareaModel');
    $subTareaModel = model('SubtareaModel');
    
    if (!$tareaModel->puedeEditarTarea($id, session()->get('id'))) {
        return redirect()->to('/dashboard')->with('error', 'No tienes permiso para ver esta tarea');
    }

    $tarea = $tareaModel->select('tareas.*, usuarios.nombre as usuario_nombre')
                       ->join('usuarios', 'usuarios.id = tareas.id_usuario')
                       ->find($id);

    $subtareas = $subTareaModel->select('subtareas.*, usuarios.nombre as responsable_nombre')
                              ->join('usuarios', 'usuarios.id = subtareas.id_responsable', 'left')
                              ->where('id_tarea', $id)
                              ->findAll();

    $usuarios = model('UsuarioModel')->findAll();

    return view('/tareas/verTarea', [
        'tarea' => $tarea,
        'subtareas' => $subtareas,
        'usuarios' => $usuarios,
        'tareaModel' => $tareaModel,
        'subtareaModel' => $subTareaModel
    ]);
}


}