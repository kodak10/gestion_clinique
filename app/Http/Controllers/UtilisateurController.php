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
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin'])) {
            abort(403, 'Accès non autorisé.');
        }

        $users = User::with('roles')->orderBy('name')->get();

        $roles = Role::all();
        
        return view('dashboard.pages.parametrages.acces_utilisateurs', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin'])) {
            abort(403, 'Accès non autorisé.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'pseudo' => 'required|string|max:15|unique:users',
            'phone_number' => 'required|string|unique:users',
            'roles' => 'required|array',
            'roles.*' => 'string|exists:roles,name'
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
            'password' => Hash::make('password'),
            'status' => 'Actif'
        ]);

        $user->syncRoles($request->roles);

        return redirect()->route('utilisateurs.index')
            ->with('success', 'Utilisateur créé avec succès!');
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasAnyRole(['Developpeur', 'Admin'])) {
            abort(403, 'Accès non autorisé.');
        }

        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'roles' => 'required|array',
            'roles.*' => 'string|exists:roles,name'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'name' => $request->name,
        ]);

        $user->syncRoles($request->roles);

        return redirect()->route('utilisateurs.index')
            ->with('success', 'Utilisateur mis à jour avec succès!');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status == 'Actif' ? 'Inactif' : 'Actif';
        $user->save();

        return back()->with('success', 'Statut utilisateur mis à jour');
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
