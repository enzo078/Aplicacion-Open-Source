<?php

namespace App\Models;

use CodeIgniter\Model;

class SubtareaModel extends Model{
    protected $table = 'subtareas';
    protected $primaryKey = 'id';
    protected $allowedFields = ['descripcion', 'estado', 'prioridad', 'fecha_vencimiento',
                                 'asignado_es_admin', 'id_responsable', 'id_tarea', 'color', 'asunto' ];
    protected $useTimestamps = true;
    

    public function puedeEditarSubtarea($subtareaId, $usuarioId) {
        $subtarea = $this->find($subtareaId);
        $tareaModel = new TareaModel();
        
        if ($tareaModel->puedeEditarTarea($subtarea['id_tarea'], $usuarioId)) {
            return true;
        }
        
        return $subtarea['responsable_id'] == $usuarioId && $subtarea['asignado_es_admin'];
    }
    
}