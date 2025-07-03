<?php

namespace App\Http\Controllers;

use App\Models\Prestation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class TracabiliteController extends Controller
{
    public function index()
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin'])) {
            abort(403, 'Accès non autorisé.');
        }

        $activities = Activity::with('causer')->latest()->get();

        return view('dashboard.pages.tracabilite.index', compact('activities'));
    }
}
