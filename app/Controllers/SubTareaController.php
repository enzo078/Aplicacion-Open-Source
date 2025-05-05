<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SubtareaModel;
use App\Models\UsuarioModel;
use App\Models\TareaModel;

class SubTareaController extends BaseController
{
    protected $usuarioModel;
    protected $subTareaModel;

    public function __construct()
    {
        $this->subTareaModel = new SubtareaModel();
    }

    public function index()
    {
        $subtareas = $this->subTareaModel->findAll();
        return view('subtareas/listaSubtareas', ['subtareas' => $subtareas]);
    }


    public function create($tareaId)
{
    $tarea = $this->tareaModel->find($tareaId);
    
    if (!$tarea) {
        return redirect()->to('/tareas')->with('error', 'Tarea no encontrada');
    }

    $usuarioId = session()->get('id');
    $rol = session()->get('rol');

    if ($tarea['archivada'] || $tarea['estado'] === 'Completada') {
        if ($usuarioId != $tarea['id_usuario'] && $rol != 'admin') {
            return redirect()->to('/tareas')->with('error', 'No puedes crear subtareas en tareas archivadas o completadas');
        }
    }

    // Cargamos todos los usuarios como posibles responsables
    $responsables = $this->usuarioModel->findAll(); // Si querés filtrar por rol, podés hacerlo aquí

    return view('tareas/crearSubtarea', [
        'tarea' => $tarea,
        'responsables' => $responsables
    ]);
}



    public function edit($id)
    {
        $subtarea = $this->subTareaModel->find($id);
        if (!$subtarea) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Subtarea no encontrada');
        }

        return view('subtareas/editar', ['subtarea' => $subtarea]);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();
        $subtarea = $this->subTareaModel->find($id);

        if (!$subtarea) {
            return redirect()->back()->with('error', 'Subtarea no encontrada');
        }

        $tarea = $this->tareaModel->find($subtarea['tarea_id']);
        $usuarioId = session()->get('id');
        $rol = session()->get('rol');

        if ($tarea['archivada'] || $tarea['estado'] === 'Completada') {
            if ($usuarioId != $tarea['id_usuario'] && $rol != 'admin') {
                return redirect()->back()->with('error', 'No puedes editar subtareas de tareas archivadas o completadas');
            }
        }

        if (!$this->subTareaModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $this->subTareaModel->errors());
        }

        $this->actualizarEstadoTarea($subtarea['tarea_id']);
        return redirect()->to('/tareas/' . $subtarea['tarea_id'])->with('success', 'Subtarea actualizada');
    }

    public function delete($id = null)
    {
        $subtarea = $this->subTareaModel->find($id);
        if (!$subtarea) {
            return redirect()->back()->with('error', 'Subtarea no encontrada');
        }

        $tarea = $this->tareaModel->find($subtarea['tarea_id']);
        $usuarioId = session()->get('id');

        if ($usuarioId != $tarea['id_usuario']) {
            return redirect()->back()->with('error', 'Solo el dueño de la tarea puede eliminar esta subtarea');
        }

        $this->subTareaModel->delete($id);
        $this->actualizarEstadoTarea($subtarea['tarea_id']);
        return redirect()->to('/tareas/' . $subtarea['tarea_id'])->with('success', 'Subtarea eliminada');
    }

    protected function actualizarEstadoTarea($tareaId)
    {
        $subtareas = $this->subTareaModel->where('tarea_id', $tareaId)->findAll();
        $total = count($subtareas);
        $completadas = count(array_filter($subtareas, fn($s) => $s['estado'] === 'Completada'));

        if ($total > 0 && $completadas === $total) {
            $estado = 'Completada';
        } elseif ($completadas > 0) {
            $estado = 'En proceso';
        } else {
            $estado = 'Definida';
        }

        $this->tareaModel->update($tareaId, ['estado' => $estado]);
    }
}