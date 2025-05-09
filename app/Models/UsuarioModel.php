<?php namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre', 'username', 'email', 'password', 'rol', 'es_admin'];
    protected $useAutoIncrement = true;
    
    protected $beforeInsert = ['setDefaultValues'];
    
    protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[100]',
        'username' => 'required|min_length[3]|max_length[20]|is_unique[usuarios.username]',
        'email' => 'required|valid_email|is_unique[usuarios.email]',
        'password' => 'required|min_length[8]',
        'rol' => 'permit_empty|in_list[usuario,admin]' // Opcional: si manejas roles
    ];
    
    protected $validationMessages = [
        'username' => [
            'is_unique' => 'Este nombre de usuario ya está en uso'
        ],
        'email' => [
            'is_unique' => 'Este correo ya está registrado'
        ],
        'password' => [
            'min_length' => 'La contraseña debe tener al menos 8 caracteres'
        ]
    ];
    
    /**
     * Establece valores por defecto antes de insertar
     */
    protected function setDefaultValues(array $data)
    {
        if (!isset($data['data']['es_admin'])) {
            $data['data']['es_admin'] = 0; // Valor por defecto
        }
        
        if (!isset($data['data']['rol'])) {
            $data['data']['rol'] = 'usuario'; // Valor por defecto
        }
        
        return $data;
    }
}