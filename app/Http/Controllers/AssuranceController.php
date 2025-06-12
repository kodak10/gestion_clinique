<?php

namespace App\Http\Controllers;

use App\Models\Assurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssuranceController extends Controller
{
    public function index()
    {
        $assurances = Assurance::all();
        return view('dashboard.pages.parametrages.assurances', compact('assurances'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'taux' => 'required|numeric',
            'phone_number' => 'required|string',
            'email' => 'nullable|email',
            'siege' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('assurances', 'public');
        }

        Assurance::create($validated);

        return redirect()->route('assurances.index')
            ->with('success', 'Assurance créée avec succès!');
    }

    public function update(Request $request, Assurance $assurance)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'taux' => 'required|numeric',
            'phone_number' => 'required|string',
            'email' => 'nullable|email',
            'siege' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($assurance->image) {
                Storage::disk('public')->delete($assurance->image);
            }
            $validated['image'] = $request->file('image')->store('assurances', 'public');
        }

        $assurance->update($validated);

        return redirect()->route('assurances.index')
            ->with('success', 'Assurance mise à jour avec succès!');
    }
}
