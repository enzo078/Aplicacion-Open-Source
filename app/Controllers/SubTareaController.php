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
    protected $tareaModel;

    public function __construct()
    {
        $this->subTareaModel = new SubTareaModel();
        $this->tareaModel = new TareaModel();
        $this->usuarioModel = new UsuarioModel();
    }

    public function index()
    {
        $subtareas = $this->subTareaModel->findAll();
        return view('subtareas/listaSubtareas', ['subtareas' => $subtareas]);
    }

public function crear()
{
    $tareaId = $this->request->getPost('id_tarea');

    log_message('info', 'Datos recibidos: ' . json_encode($this->request->getPost()));

    $rules = [
        'id_tarea' => 'required|is_not_unique[tareas.id]',
        'asunto' => 'required|max_length[150]',
        'descripcion' => 'required',
        'prioridad' => 'required|in_list[Baja,Normal,Alta]',
        'id_responsable' => 'permit_empty|is_not_unique[usuarios.id]',
        'asignado_es_admin' => 'permit_empty|in_list[0,1]',
        'fecha_vencimiento' => 'permit_empty|valid_date'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    try {
        $db = \Config\Database::connect();
        $db->transStart();

        $dataSubtarea = [
            'id_tarea' => $tareaId,
            'asunto' => $this->request->getPost('asunto') ?: null,
            'descripcion' => $this->request->getPost('descripcion'),
            'prioridad' => $this->request->getPost('prioridad'),
            'id_responsable' => $this->request->getPost('id_responsable') ?: null,
            'asignado_es_admin' => $this->request->getPost('asignado_es_admin') ? 1 : 0,
            'estado' => 'En proceso',
            'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento') ?: null
        ];

        log_message('info', 'Datos a insertar: ' . json_encode($dataSubtarea));

        if (!$this->subTareaModel->insert($dataSubtarea)) {
            throw new \RuntimeException('Error al insertar: ' . implode(', ', $this->subTareaModel->errors()));
        }

        $tarea = $this->tareaModel->find($tareaId);
        if ($tarea['estado'] === 'Definida') {
            $this->tareaModel->update($tareaId, ['estado' => 'En proceso']);
        }

        $idResponsable = $this->request->getPost('id_responsable');
        if (!empty($idResponsable)) {
            $this->_notificarAsignacion(
                $idResponsable,
                $dataSubtarea['asunto'],
                $tareaId,
                $dataSubtarea['fecha_vencimiento'],
                $dataSubtarea['prioridad']
            );
        }

        $db->transComplete();

        return redirect()->to("/tareas/ver/{$tareaId}")->with('message', 'Subtarea creada exitosamente');

    } catch (\Exception $e) {
        isset($db) && $db->transRollback();
        log_message('error', 'Error en crear(): ' . $e->getMessage());
        return redirect()->back()->withInput()->with('error', $e->getMessage());
    }
}


private function _notificarAsignacion($idUsuario, $asuntoSubtarea, $idTarea, $fechaVencimiento, $prioridad)
{
    try {
        $usuarioModel = new \App\Models\UsuarioModel();
        $usuario = $usuarioModel->find($idUsuario);
        
        if (!$usuario || empty($usuario['email'])) {
            log_message('warning', "No se pudo notificar al usuario ID {$idUsuario} - Email no encontrado");
            return false;
        }

        $email = \Config\Services::email();
        
        $email->setTo($usuario['email']);
        $email->setSubject('Has sido asignado a una nueva subtarea');
        
        $mensaje = view('emails/asignacion_subtarea', [
            'nombre' => $usuario['username'],
            'asuntoSubtarea' => $asuntoSubtarea,
            'idTarea' => $idTarea,
            'fechaVencimiento' => $fechaVencimiento,
            'prioridad' => $prioridad,
            'base_url' => base_url()
        ]);
        
        $email->setMessage($mensaje);
        
        if (!$email->send()) {
            log_message('error', 'Error al enviar email: ' . $email->printDebugger(['headers']));
            return false;
        }
        
        log_message('info', "Notificación enviada a {$usuario['email']} sobre subtarea {$asuntoSubtarea}");
        return true;
        
    } catch (\Exception $e) {
        log_message('error', 'Error en _notificarAsignacion: ' . $e->getMessage());
        return false;
    }
}

    public function editar($subtareaId)
{
    $subtarea = $this->subTareaModel
        ->select('subtareas.*, usuarios.username as responsable_nombre, usuarios.id as responsable_id')
        ->join('usuarios', 'usuarios.id = subtareas.id_responsable', 'left')
        ->find($subtareaId);
        
    return $this->response->setJSON($subtarea);
}

    public function update($id = null)
    {
        $data = $this->request->getPost();
        $subtarea = $this->subTareaModel->find($id);

        if (!$subtarea) {
            return redirect()->back()->with('error', 'Subtarea no encontrada');
        }

        $tarea = $this->tareaModel->find($subtarea['id_tarea']);
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

        $this->actualizarEstadoTarea($subtarea['id_tarea']); 
        return redirect()->to('/tareas/' . $subtarea['id_tarea'])->with('success', 'Subtarea actualizada'); 
    }

   
public function cambiarEstado()
{
    $validation = \Config\Services::validation();

    if (!$this->validate([
        'id' => 'required|integer',
        'estado' => 'required|string'
    ])) {
        return $this->response->setJSON(['success' => false, 'message' => 'Datos inválidos']);
    }

    $subtareaId = $this->request->getPost('id');
    $nuevoEstado = $this->request->getPost('estado');

    $subtarea = $this->subTareaModel->select('subtareas.*, tareas.estado as tarea_estado')
                                   ->join('tareas', 'tareas.id = subtareas.id_tarea')
                                   ->find($subtareaId);

    if ($subtarea['id_responsable'] != session()->get('id')) {
        return $this->response->setJSON(['success' => false, 'message' => 'No tienes permiso']);
    }

    $this->subTareaModel->update($subtareaId, ['estado' => $nuevoEstado]);

    $todasSubtareas = $this->subTareaModel->where('id_tarea', $subtarea['id_tarea'])->findAll();

    $todasCompletadas = true;
    $alMenosUnaEnProceso = false;

    foreach ($todasSubtareas as $st) {
        if ($st['estado'] != 'Completada') {
            $todasCompletadas = false;
        }
        if ($st['estado'] == 'En Proceso') {
            $alMenosUnaEnProceso = true;
        }
    }

    $nuevoEstadoTarea = null;

    if ($todasCompletadas) {
        $nuevoEstadoTarea = 'Completada';
    } elseif ($alMenosUnaEnProceso || $nuevoEstado == 'En Proceso') {
        $nuevoEstadoTarea = 'En Proceso';
    }

    if ($nuevoEstadoTarea && $nuevoEstadoTarea != $subtarea['tarea_estado']) {
        $this->tareaModel->update($subtarea['id_tarea'], ['estado' => $nuevoEstadoTarea]);
    }

    return $this->response->setJSON([
        'success' => true,
        'message' => 'Estado actualizado',
        'nuevo_estado' => $nuevoEstado,
        'nuevo_estado_tarea' => $nuevoEstadoTarea ?? $subtarea['tarea_estado']
    ]);
}

public function eliminar()
{
    $subtareaId = $this->request->getPost('id');
    $subtarea = $this->subTareaModel->find($subtareaId);

    if (!$subtarea) {
        return redirect()->back()->with('error', 'Subtarea no encontrada');
    }

    if ($subtarea['id_creador'] != session()->get('id')) {
        return redirect()->back()->with('error', 'No tienes permiso para esta acción');
    }

    $this->subTareaModel->delete($subtareaId);
    return redirect()->back()->with('success', 'Subtarea eliminada correctamente');
}




}