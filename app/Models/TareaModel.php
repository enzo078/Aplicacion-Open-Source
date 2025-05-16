<?php

namespace App\Models;

use CodeIgniter\Model;

class TareaModel extends Model
{
    protected $table = 'tareas';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_usuario', 'asunto', 'descripcion', 'prioridad', 'estado',
        'fecha_vencimiento', 'fecha_recordatorio', 'color', 'archivada',
    ];
    protected $useTimestamps = true;

    protected $validationRules = [
        'id_usuario' => 'required|is_not_unique[usuarios.id]',
        'asunto' => 'required|min_length[3]',
        'descripcion' => 'required',
        'fecha_vencimiento' => 'required|valid_date[Y-m-d]',
        'fecha_recordatorio' => 'permit_empty|valid_date[Y-m-d]',
    ];

    protected $validationMessages = [
        'asunto' => [
            'required' => 'El asunto es obligatorio.',
            'min_length' => 'El asunto debe tener al menos 3 caracteres.',
        ],
        'descripcion' => [
            'required' => 'La descripción es obligatoria.',
        ],
        'fecha_vencimiento' => [
            'required' => 'La fecha de vencimiento es obligatoria.',
            'valid_date' => 'La fecha de vencimiento debe tener el formato YYYY-MM-DD.',
        ],
        'fecha_recordatorio' => [
            'valid_date' => 'La fecha de recordatorio debe tener el formato YYYY-MM-DD.',
        ],
    ];
    
    public function getTareasByUserId($usuarioId)
    {
        return $this->where('id', $usuarioId)->findAll();
    }

    public function puedeEditarTarea($tareaId, $usuarioId) 
    {
        $tarea = $this->find($tareaId);
        
        if (!$tarea) {
            log_message('error', "Tarea no encontrada: ID {$tareaId}");
            return false;
        }
        
        // Administradores tienen acceso completo
        if (session()->get('rol') == 'admin') {
            return true;
        }
        
        // El creador de la tarea tiene acceso
        if ($tarea['id_usuario'] == $usuarioId) {
            return true;
        }
        
        // Verificar si es responsable de subtareas
        $subtareaModel = model('SubtareaModel');
        $tieneSubtareas = $subtareaModel->where('id_tarea', $tareaId)
                                       ->where('id_responsable', $usuarioId)
                                       ->countAllResults();
        
        // Si tiene permisos de admin en alguna subtarea
        $tienePermisosAdmin = $subtareaModel->where('id_tarea', $tareaId)
                                           ->where('id_responsable', $usuarioId)
                                           ->where('asignado_es_admin', 1)
                                           ->countAllResults();
        
        return ($tieneSubtareas > 0) || ($tienePermisosAdmin > 0);
    }
}




