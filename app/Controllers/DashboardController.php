<?php 
namespace App\Controllers;

use App\Models\TareaModel;
use App\Models\SubTareaModel;

class DashboardController extends BaseController
{
    protected $tareaModel;
    protected $subTareaModel;

    public function __construct()
    {
        $this->tareaModel = new TareaModel();
        $this->subTareaModel = new SubTareaModel();
    }

    public function index()
{
    if (!session()->get('loggedIn')) {
        return redirect()->to('/auth/login');
    }

    $filter = $this->request->getGet('filter');
    $userId = session()->get('id');

    // Consulta CORREGIDA (usar id_usuario en lugar de id)
    $query = $this->tareaModel->where('id_usuario', $userId);

    switch ($filter) {
        case 'completed':
            $query->where('estado', 'completada');
            $active_filter = 'completed';
            break;
        case 'assigned':
            $query->where('asignada_por IS NOT NULL');
            $active_filter = 'assigned';
            break;
        case 'archived':
            $query->where('archivada', 1);
            $active_filter = 'archived';
            break;
        default:
        $query->where('archivada', 0);
        $active_filter = null;
    }

    $tareas = $query->findAll();

    foreach ($tareas as &$tarea) {
        $tarea['subtareas'] = $this->subTareaModel->where('id_tarea', $tarea['id'])->findAll();
    }

    return view('dashboard/index', [
        'title' => 'Dashboard',
        'tareas' => $tareas,
        'active_filter' => $active_filter
    ]);
}

    public function verTarea($id)
    {
        $tarea = $this->tareaModel->find($id);
        $subtareas = $this->subTareaModel->where('id_tarea', $id)->findAll();

        return view('tareas', [
            'tarea' => $tarea,
            'subtareas' => $subtareas
        ]);
    }

    public function editarTarea($id)
    {
        $tarea = $this->tareaModel->find($id);

        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost();
            $this->tareaModel->update($id, $data);
            return redirect()->to('/dashboard')->with('success', 'Tarea actualizada');
        }

        return view('tareas/editarTarea', [
            'tarea' => $tarea
        ]);
    }
}