<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

class TracabiliteController extends Controller
{
    public function index()
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin'])) {
            abort(403, 'Accès non autorisé.');
        }

        return view('dashboard.pages.tracabilite.index');
    }

    public function getTracabiliteData(Request $request)
    {
        $activities = Activity::with(['causer' => function($query) {
                            $query->select('id', 'name', 'pseudo');
                        }])
                        ->where('causer_id', '!=', 1)
                        ->select('id', 'event', 'subject_type', 'subject_id', 'causer_type', 'causer_id', 'properties', 'created_at')
                        ->latest();

        return DataTables::eloquent($activities)
            ->addIndexColumn()
            // Colonne Action
            ->addColumn('action', function($activity) {
                $status = [
                    'created' => 'Création',
                    'updated' => 'Modification',
                    'deleted' => 'Suppression',
                    'restored' => 'Restauration',
                ];
                
                $badgeClass = match($activity->event) {
                    'created' => 'bg-success',
                    'updated' => 'bg-warning',
                    'deleted' => 'bg-danger',
                    'restored' => 'bg-info',
                    default => 'bg-secondary',
                };

                return '<span class="badge '.$badgeClass.'">'.($status[$activity->event] ?? ucfirst($activity->event)).'</span>';
            })
            // Colonne Modèle
            ->addColumn('model', function($activity) {
                return class_basename($activity->subject_type);
            })
            // Colonne ID
            ->addColumn('subject_id', function($activity) {
                return $activity->subject_id;
            })
            // Colonne Utilisateur
            ->addColumn('user', function($activity) {
                $user = $activity->causer;
                if (!$user) return 'Système';
                
                $html = $user->name;
                if ($user->pseudo) {
                    $html .= '<br><small class="text-muted">'.$user->pseudo.'</small>';
                }
                return $html;
            })
            // Colonne Ancien
            ->addColumn('old', function($activity) {
                if ($activity->event == 'updated' && $activity->properties->has('old')) {
                    $html = '<ul class="list-unstyled mb-0">';
                    foreach ($activity->properties['old'] as $key => $value) {
                        $html .= '<li><strong>'.$key.':</strong> ';
                        $html .= is_array($value) || is_object($value) 
                            ? '<pre class="mb-0">'.json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT).'</pre>'
                            : ($value ?? 'NULL');
                        $html .= '</li>';
                    }
                    return $html.'</ul>';
                } elseif ($activity->event == 'deleted') {
                    $html = '<div class="alert alert-danger p-2 mb-0">';
                    if ($activity->properties->has('old')) {
                        $html .= '<ul class="mb-0">';
                        foreach ($activity->properties['old'] as $key => $value) {
                            $html .= '<li><strong>'.$key.':</strong> ';
                            $html .= is_array($value) || is_object($value) 
                                ? '<pre class="mb-0">'.json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT).'</pre>'
                                : ($value ?? 'NULL');
                            $html .= '</li>';
                        }
                        $html .= '</ul>';
                    } else {
                        $html .= '<em>Données non disponibles</em>';
                    }
                    return $html.'</div>';
                }
                return '<em class="text-muted">N/A</em>';
            })
            // Colonne Nouveau
            ->addColumn('new', function($activity) {
                if ($activity->properties->has('attributes')) {
                    $html = '<ul class="list-unstyled mb-0">';
                    foreach ($activity->properties['attributes'] as $key => $value) {
                        $html .= '<li><strong>'.$key.':</strong> ';
                        $html .= is_array($value) || is_object($value) 
                            ? '<pre class="mb-0">'.json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT).'</pre>'
                            : ($value ?? 'NULL');
                        $html .= '</li>';
                    }
                    return $html.'</ul>';
                } elseif ($activity->event == 'deleted') {
                    return '<em class="text-muted">Supprimé</em>';
                }
                return '<em class="text-muted">N/A</em>';
            })
            // Colonne Date
            ->addColumn('date', function($activity) {
                return $activity->created_at->format('d/m/Y à H:i');
            })
            ->rawColumns(['action', 'user', 'old', 'new'])
            ->make(true);
    }
}