<?php

namespace App\Http\Controllers;

use App\Models\Prestation;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class TracabiliteController extends Controller
{
    public function index()
    {
        $activities = Activity::with('causer')->latest()->get();

        return view('dashboard.pages.tracabilite.index', compact('activities'));
    }
}
