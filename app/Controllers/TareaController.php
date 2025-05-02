<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TareaModel;
use App\Models\SubtareaModel;

class TareaController extends BaseController{

    protected $tareaModel;

    public function __construct(){
        $this->tareaModel = new TareaModel(); 
    }

    //Obtener todas las tareas
    public function index()
    {
        $tareas = $this->tareaModel->findAll();

        $subtareaModel = new SubtareaModel();

        foreach ($tareas as &$tarea) {
            $tarea['subtareas'] = $subtareaModel->where('tarea_id', $tarea['id'])->findAll();
        }
        // Pasamos las tareas (con subtareas) a la vista
        return view('tareas/index', ['tareas' => $tareas]);
    }

    //Obtener una tarea por id
    public function show($id = null){
        $tarea = $this->tareaModel->find($id);
        if($tarea){
            return this->respond($tarea);
        }else{
            return $this->failNotFound('No se encontro la tarea');
        }
    }

    public function crearTarea()
    {
        return view('tareas/crearTarea');
    }

    //Crear nueva tarea
    public function create()
{
    $data = $this->request->getPost();

    if (!$this->validate($this->tareaModel->validationRules)) {
        return redirect()->back()->withInput()->with('validation', \Config\Services::validation());
    }
    if (!$this->tareaModel->insert($data)) {
        return redirect()->back()->with('error', 'Hubo un problema al crear la tarea.');
    }
    return redirect()->to('/tareas')->with('success', 'Tarea creada correctamente.');
}

//Editar tarea
public function editar($id)
{
    $tarea = $this->tareaModel->find($id);
    if (!$tarea) {
        return redirect()->to('/tareas')->with('error', 'Tarea no encontrada.');
    }

    return view('editar_tarea', ['tarea' => $tarea]);
}

//Actualizar tarea
public function actualizar($id)
{
    $data = $this->request->getPost();

    if (!$this->validate([
        'asunto' => 'required|min_length[3]',
        'descripcion' => 'required',
        'fecha_vencimiento' => 'required|valid_date',
    ])) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $this->tareaModel->update($id, $data);

    return redirect()->to('/tareas')->with('message', 'Tarea actualizada');
}

//Eliminar tarea
public function eliminar($id)
{
    $tarea = $this->tareaModel->find($id);
    $session = session();
    $usuario_id = $session->get('usuario_id');

    if ($tarea['usuario_id'] != $usuario_id && !$session->get('es_admin')) {
        return redirect()->to('/tareas')->with('error', 'No tienes permiso para eliminar esta tarea');
    }

    $this->tareaModel->delete($id);

    return redirect()->to('/tareas')->with('message', 'Tarea eliminada');
}

//Archivar tarea
public function archivar($id)
{
    $this->tareaModel->update($id, ['estado' => 'archivada']);

    return redirect()->to('/tareas')->with('message', 'Tarea archivada');
}


}