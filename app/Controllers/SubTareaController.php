<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SubtareaModel;

class SubTareaController extends BaseController
{
    protected $subTareaModel;

    public function __construct()
    {
        $this->subTareaModel = new SubtareaModel();
    }

    // Obtener todas las subtareas
    public function index()
    {
        $subtareas = $this->subTareaModel->findAll();
        return $this->respond($subtareas);
    }

    // Obtener una subtarea por ID
    public function show($id = null)
    {
        $subtarea = $this->subTareaModel->find($id);
        if ($subtarea) {
            return $this->respond($subtarea);
        } else {
            return $this->failNotFound('No se encontró la subtarea');
        }
    }

    // Crear nueva subtarea
    public function create()
    {
        $data = $this->request->getPost();

        if ($this->subTareaModel->insert($data)) {
            return $this->respondCreated(['message' => 'Subtarea creada']);
        }
        return $this->fail('Error al crear la subtarea');
    }

    // Actualizar subtarea
    public function update($id = null)
    {
        $data = $this->request->getRawInput();

        if ($this->subTareaModel->update($id, $data)) {
            return $this->respond(['message' => 'Subtarea actualizada']);
        }

        return $this->fail('Error al actualizar la subtarea');
    }

    // Eliminar subtarea
    public function delete($id = null)
    {
        if ($this->subTareaModel->delete($id)) {
            return $this->respondDeleted(['message' => 'Subtarea eliminada correctamente']);
        }
        return $this->fail('Error al eliminar la subtarea');
    }
}
