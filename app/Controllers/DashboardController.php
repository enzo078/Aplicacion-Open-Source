<?php 
namespace App\Controllers;

use App\Models\TareaModel;
use App\Models\SubTareaModel;
use App\Models\UsuarioModel;

class DashboardController extends BaseController
{
    protected $tareaModel;
    protected $subTareaModel;
    protected $usuarioModel;

    public function __construct()
    {
        $this->tareaModel = new TareaModel();
        $this->subTareaModel = new SubTareaModel();
        $this->usuarioModel = new UsuarioModel();
    }

    public function index()
    {
        if (!session()->get('loggedIn')) {
            return redirect()->to('/auth/login');
        }

        $filter = $this->request->getGet('filter');
        $userId = session()->get('id');
        
        if ($filter === 'subtareas') {
            return $this->misSubtareas();
        }

        $query = $this->tareaModel->where('id_usuario', $userId);

        switch ($filter) {
            case 'completed':
                $query->where('estado', 'Completada')->where('archivada', 0);
                $active_filter = 'completed';
                break;
            case 'pending':
                $query->where('estado', 'En Proceso')->where('archivada', 0);
                $active_filter = 'pending';
                break;
            case 'assigned':
                $query->where('asignada_por IS NOT NULL')->where('archivada', 0);
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
        
            $tarea['todas_subtareas_completadas'] = true;
            foreach ($tarea['subtareas'] as $subtarea) {
                if ($subtarea['estado'] !== 'Completada') {
                    $tarea['todas_subtareas_completadas'] = false;
                    break;
                }
            }
        }
        

        return view('dashboard/index', [
            'title' => 'Dashboard',
            'tareas' => $tareas,
            'active_filter' => $active_filter
        ]);
    }

    public function misSubtareas()
{
    $userId = session()->get('id');
    
    $subtareasAsignadas = $this->subTareaModel
        ->distinct() 
        ->where('id_responsable', $userId)
        ->findAll();
    
    if (empty($subtareasAsignadas)) {
        return view('dashboard/index', [
            'title' => 'Mis Subtareas',
            'tarea' => null,
            'subtareas' => [],
            'usuarios' => $this->usuarioModel->findAll(),
            'active_filter' => 'subtareas'
        ]);
    }
    
    $responsableIds = array_filter(array_unique(array_column($subtareasAsignadas, 'id_responsable')));
    
    $nombresResponsables = [];
    if (!empty($responsableIds)) {
        $responsables = $this->usuarioModel
            ->whereIn('id', $responsableIds)
            ->findAll();
        
        foreach ($responsables as $responsable) {
            $nombresResponsables[$responsable['id']] = $responsable['username'];
        }
    }
    
    $tareaIds = array_unique(array_column($subtareasAsignadas, 'id_tarea'));
    
    $tareas = $this->tareaModel
        ->select('tareas.id, tareas.asunto as tarea_asunto, tareas.estado as tarea_estado')
        ->whereIn('id', $tareaIds)
        ->where('archivada', 0)
        ->findAll();
    
    $infoTareas = [];
    foreach ($tareas as $tarea) {
        $infoTareas[$tarea['id']] = [
            'asunto' => $tarea['tarea_asunto'],
            'estado' => $tarea['tarea_estado']
        ];
    }
    
    $subtareasParaVista = [];
    foreach ($subtareasAsignadas as $subtarea) {
        if (isset($infoTareas[$subtarea['id_tarea']])) {
            $subtarea['tarea_asunto'] = $infoTareas[$subtarea['id_tarea']]['asunto'];
            $subtarea['responsable_nombre'] = $nombresResponsables[$subtarea['id_responsable']] ?? 'Sin asignar';
            $subtareasParaVista[] = $subtarea;
        }
    }
    
    return view('dashboard/index', [
        'title' => 'Mis Subtareas',
        'tarea' => null,
        'subtareas' => $subtareasParaVista,
        'usuarios' => $this->usuarioModel->findAll(),
        'active_filter' => 'subtareas'
    ]);
}
    public function verTarea($id)
    {
        $tarea = $this->tareaModel->find($id);
        if (!$tarea) {
            return redirect()->back()->with('error', 'Tarea no encontrada');
        }

        $subtareas = $this->subTareaModel->where('id_tarea', $id)->findAll();
        $userId = session()->get('id');
        
        foreach ($subtareas as &$subtarea) {
            $subtarea['es_mia'] = ($subtarea['id_responsable'] == $userId);
        }

        return view('tareas/ver', [
            'tarea' => $tarea,
            'subtareas' => $subtareas,
            'active_filter' => null
        ]);
    }
}