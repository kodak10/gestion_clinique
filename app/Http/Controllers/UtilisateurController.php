<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
class UtilisateurController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return view('dashboard.pages.parametrages.acces_utilisateurs', compact('users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'pseudo' => 'required|string|max:15|unique:users',
            'phone_number' => 'required|string|unique:users',
            'role' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'pseudo' => $request->pseudo,
            'phone_number' => $request->phone_number,
            'password' => Hash::make('password'), // Mot de passe par défaut
            'status' => 'Actif'
        ]);

        $user->assignRole($request->role);

        return redirect()->route('utilisateurs.index')
            ->with('success', 'Utilisateur créé avec succès!');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status == 'Actif' ? 'Inactif' : 'Actif';
        $user->save();

        return redirect()->back()->with('success', 'Statut utilisateur mis à jour!');
    }
}
