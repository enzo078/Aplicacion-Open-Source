<?php

namespace App\Controller;
use App\Controllers\BaseController;
use App\Models\TareaModel;

class SubTareaController extends BaseController{

    protected $subTareaModel;

    public function __construct(){
        $this->subTareaModel = new SubtareaModel(); 
    }

    //Obtener todas las subtareas
    public function index(){
        $subtareas = $this->subTareaModel->findAll();
        return $this->respond($usuarios);
    }

    //Obtener una subtarea por id
    public function show($id = null){
        $tarea = $this->subTareaModel->find($id);
        if($tarea){
            return this->respond($tarea);
        }else{
            return $this->failNotFound('No se encontro la tarea');
        }
    }

    //Crear nueva subtarea
    public function create(){
        $data = this->request->getPost();

        if($this->subTareaModel->insert($data)){
            return $this->respondCreatead(['message' => 'Tarea creada'])
        }
        return $this->('Error al crear la tarea');
    }

    //Actualizar subtarea
    public function update($id = null){
        $data = $this->request->getRawImput();

        if ($this->subTareaModel->update($id, $data)) {
            return $this->respond(['message' => 'Tarea actualizada']);
        }

        return $this->fail('Error al actualizar la tarea');
    }

    //Eliminar subtarea
    public function delete($id = null){
        if($this->subTareaModel->delete($id)){
            return $this->respondDeleted(['message' => 'Tarea eliminada correctamente']);
        }
        return $this->fail('Error al eliminar la tarea');
    }
}