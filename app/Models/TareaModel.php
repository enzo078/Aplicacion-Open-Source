<?php

namespace App\Models;

use CodeIgniter\Model;

class TareaModel extends Model
{
    protected $table = 'tareas';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'asunto', 'descripcion', 'prioridad', 'estado',
        'fecha_vencimiento', 'fecha_recordatorio', 'color', 'archivada'
    ];
    protected $useTimestamps = true;

    protected $validationRules = [
        'asunto' => 'required|min_length[3]',
        'descripcion' => 'required',
        'fecha_vencimiento' => 'required|valid_date[Y-m-d]',
        'fecha_recordatorio' => 'permit_empty|valid_date[Y-m-d]', // Opcional
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
    

}


