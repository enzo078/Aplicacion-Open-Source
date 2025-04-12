<?php

namespace App\Models;

use CodeIgniter\Model;

class TareaModel extends Model{
    protected $table = 'tareas';
    protected $primaryKey = 'id';
    protected $allowedFields = ['asunto', 'descripcion', 'prioridad', 'estado', 'fecha_vencimiento', 'fecha_recordatorio', 'color'];

    protected $useTimestamps = true;
}

