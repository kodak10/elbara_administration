<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        // Récupérer les utilisateurs dont le rôle est différent de 'user' et 'livreur'
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['user', 'livreur']);
        })->get();

        return view('pages.users.index', compact('users'));
    }

    public function profil()
    {
        return view('pages.users.profil');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:15',  // Ajout du champ numéro de téléphone
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'nullable|min:8|confirmed',  // Validation du mot de passe
            'current_password' => 'nullable|current_password', // Validation du mot de passe actuel (optionnel)
        ]);

        // Mise à jour de l'avatar si une nouvelle image est uploadée
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            if ($user->image && Storage::exists($user->image)) {
                Storage::delete($user->image);  // Supprimer l'ancien avatar
            }
            $user->image = $avatarPath;
        }

        // Mise à jour des informations du profil
        $user->name = $validated['name'];
        $user->phone_number = $validated['phone_number'];

        // Mise à jour du mot de passe si fourni
        if ($request->filled('current_password') && $request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Retourner à la vue de profil avec un message de succès
        return redirect()->route('profil.edit')->with('success', 'Profil mis à jour avec succès.');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:15',
            'role' => 'required|string',
        ]);
    
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
    
        // Définir le mot de passe par défaut
        $user->password = bcrypt('Elbara2025'); // Mot de passe par défaut
        $user->phone_number = $request->phone_number;
        $user->status = "actif";
    
        // Ajouter le rôle
        $user->assignRole($request->role);
    
        $user->save();
    
        return redirect()->route('utilisateurs.index')->with('success', 'Utilisateur créé avec succès');
    }

    public function updateStatus($id)
    {
        // Trouver l'utilisateur par son ID
        $user = User::findOrFail($id);

        // Inverser le statut de l'utilisateur (actif -> inactif ou inactif -> actif)
        $user->status = ($user->status === 'actif') ? 'inactif' : 'actif';

        // Sauvegarder les modifications
        $user->save();

        // Rediriger vers la page précédente avec un message de succès
        return redirect()->route('utilisateurs.index')->with('success', 'Statut mis à jour avec succès');
    }
    
}
