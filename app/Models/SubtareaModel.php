<?php

namespace App\Models;

use CodeIgniter\Model;

class SubtareaModel extends Model{
    protected $table = 'subtareas';
    protected $primaryKey = 'id';
    protected $allowedFields = ['descripcion', 'estado', 'prioridad', 'fecha_vencimiento','comentario', 'responsable_id', 'tarea_id', 'color'];
    protected $useTimestamps = true;
    }