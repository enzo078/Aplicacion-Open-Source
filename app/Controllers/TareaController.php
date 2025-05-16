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
    $data['estado'] = 'Definida'; 

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

    public function update($id = null)
{
    if (!$this->request->is('post')) {
        return redirect()->back()->with('error', 'Método no permitido');
    }

    $tarea = $this->tareaModel->where('id', $id)
                             ->where('id_usuario', session()->get('id'))
                             ->first();

    if (!$tarea) {
        return redirect()->back()->with('error', 'No tienes permiso para editar esta tarea');
    }

    $rules = [
        'asunto' => 'required|min_length[3]|max_length[150]',
        'descripcion' => 'permit_empty|string',
        'prioridad' => 'required|in_list[Baja,Normal,Alta]',
        'fecha_vencimiento' => 'required|valid_date',
        'fecha_recordatorio' => 'permit_empty|valid_date'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = [
        'asunto' => $this->request->getPost('asunto'),
        'descripcion' => $this->request->getPost('descripcion'),
        'prioridad' => $this->request->getPost('prioridad'),
        'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento'),
        'fecha_recordatorio' => $this->request->getPost('fecha_recordatorio') ?: null, 
    ];

    try {
        $this->tareaModel->update($id, $data);
        return redirect()->to('/tareas/ver/'.$id)->with('success', 'Tarea actualizada correctamente');
    } catch (\Exception $e) {
        log_message('error', 'Error al actualizar tarea: ' . $e->getMessage());
        return redirect()->back()->withInput()->with('error', 'Ocurrió un error al actualizar la tarea');
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
            if ($subtarea['estado'] != 'Completada') {
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
        session()->setFlashdata('error', 'No tienes permiso para esta acción');
        return redirect()->back();
    }

    if ($tarea['estado'] !== 'Completada') {
        session()->setFlashdata('error', 'La tarea debe estar completada para poder archivarse');
        return redirect()->back();
    }

    $subtareas = $this->subTareaModel->where('id_tarea', $id)->findAll();

    foreach ($subtareas as $subtarea) {
        if ($subtarea['estado'] !== 'Completada') {
            session()->setFlashdata('error', 'Todas las subtareas deben estar completadas para archivar la tarea');
            return redirect()->back();
        }
    }

    $this->tareaModel->update($id, [
        'estado' => 'Archivada',
        'archivada' => 1 
    ]);

    session()->setFlashdata('success', 'Tarea archivada correctamente');
    return redirect()->to('/dashboard?filter=archived');
}


    public function ver($id, $fromSubtarea = false)
{
    $tareaModel = model('TareaModel');
    $subTareaModel = model('SubtareaModel');

    if (!$tareaModel->puedeEditarTarea($id, session()->get('id'))) {
        return redirect()->to('/dashboard')->with('error', 'No tienes permiso para ver esta tarea o la tarea no existe');
    }
    
    if ($fromSubtarea) {
        $subtarea = $subTareaModel->find($id);
        if (!$subtarea) {
            return redirect()->to('/dashboard')->with('error', 'Subtarea no encontrada');
        }
        $id = $subtarea['id_tarea']; 
    }

    if (!$tareaModel->puedeEditarTarea($id, session()->get('id'))) {
        return redirect()->to('/dashboard')->with('error', 'No tienes permiso para ver esta tarea');
    }

    $tarea = $tareaModel->select('tareas.*, usuarios.username as usuario_nombre')
                       ->join('usuarios', 'usuarios.id = tareas.id_usuario')
                       ->find($id);

    if (!$tarea) {
        return redirect()->to('/dashboard')->with('error', 'Tarea no encontrada');
    }

    $subtareas = $subTareaModel->select('subtareas.*, usuarios.username as responsable_nombre')
                              ->join('usuarios', 'usuarios.id = subtareas.id_responsable', 'left')
                              ->where('id_tarea', $id)
                              ->orderBy('created_at', 'DESC')
                              ->findAll();

    $usuarios = model('UsuarioModel')->findAll();

    return view('/tareas/verTarea', [
        'tarea' => $tarea,
        'subtareas' => $subtareas,
        'usuarios' => $usuarios,
        'tareaModel' => $tareaModel,
        'subtareaModel' => $subTareaModel,
        'subtareaFocus' => $fromSubtarea ? $id : null 
    ]);
}

public function puedeEditarTarea($tareaId, $usuarioId) {
    $tarea = $this->find($tareaId);
    
    if (!$tarea) {
        return false;
    }

    if (session()->get('rol') == 'admin') {
        return true;
    }

    if ($tarea['id_usuario'] == $usuarioId) {
        return true;
    }

    $subtareaModel = model('SubtareaModel');
    $esColaborador = $subtareaModel
        ->where('id_tarea', $tareaId)
        ->where('id_responsable', $usuarioId)
        ->countAllResults() > 0;

    return $esColaborador;
}


}