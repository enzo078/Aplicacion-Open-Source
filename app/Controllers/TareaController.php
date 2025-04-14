<?php

namespace App\Controller;
use App\Controllers\BaseController;
use App\Models\TareaModel;

class TareaController extends BaseController{

    protected $tareaModel;

    public function __construct(){
        $this->tareaModel = new TareaModel(); 
    }

    //Obtener todas las tareas
    public function index(){
        $tareas = $this->tareModel->findAll();
        return $this->respond($usuarios);
    }

    //Obtener una tarea por id
    public function show($id = null){
        $tarea = $this->tareaModel->find($id);
        if($usuario){
            return this->respond($tarea);
        }else{
            return $this->failNotFound('No se encontro la tarea');
        }
    }

    //Crear nueva tarea
    public function create(){
        $data = this->request->getPost();

        if($this->tareaModel->insert($data)){
            return $this->respondCreatead(['message' => 'Tarea creada'])
        }
        return $this->('Error al crear la tarea');
    }

    //Actualizar tarea
    public function update($id = null){
        $data = $this->request->getRawImput();

        if ($this->usuarioModel->update($id, $data)) {
            return $this->respond(['message' => 'Tarea actualizada']);
        }
        return $this->fail('Error al actualizar la tarea');
    }

    //Eliminar tarea
    public function delete($id = null){
        if($this->usuarioModel->delete($id)){
            return $this->respondDeleted(['message' => 'Tarea eliminada correctamente']);
        }
        return $this->fail('Error al eliminar la tarea');
    }
}