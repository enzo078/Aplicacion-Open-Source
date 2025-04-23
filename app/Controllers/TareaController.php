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

    //Crear nueva tarea
    public function create(){
        $data = $this->request->getPost();

        if($this->tareaModel->insert($data)){
            return $this->respondCreated(['message' => 'Tarea creada']);
        }
        return $this->fail('Error al crear la tarea');
    }

    //Actualizar tarea
    public function update($id = null){
        $data = $this->request->getRawInput();

        if ($this->tareaModel->update($id, $data)) {
            return $this->respond(['message' => 'Tarea actualizada']);
        }
        return $this->fail('Error al actualizar la tarea');
    }

    //Eliminar tarea
    public function delete($id = null){
        if($this->tareaModel->delete($id)){
            return $this->respondDeleted(['message' => 'Tarea eliminada correctamente']);
        }
        return $this->fail('Error al eliminar la tarea');
    }
}