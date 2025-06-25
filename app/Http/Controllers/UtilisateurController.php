<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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

    public function editProfile()
    {
        $user = Auth::user();
        return view('dashboard.pages.profil.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'pseudo' => 'required|string|max:15|unique:users,pseudo,' . $user->id,
            'phone_number' => 'required|string|unique:users,phone_number,' . $user->id,
            'current_password' => 'nullable|required_with:new_password|string',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->pseudo = $request->pseudo;
        $user->phone_number = $request->phone_number;

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'Le mot de passe actuel est incorrect.');
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Profil mis à jour avec succès.');
    }
}
