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
    $user = Auth::user();
    return view('pages.users.profil', compact('user'));
}

public function updateProfile(Request $request)
{
    $user = Auth::user();

    // Validation des données
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'phone_number' => 'nullable|string|max:15',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'current_password' => 'required_with:password',
        'password' => 'nullable|min:8|confirmed',
    ]);

    // Mise à jour de l'avatar
    if ($request->hasFile('avatar')) {
        // Supprimer l'ancien avatar s'il existe (sauf l'image par défaut)
        if ($user->image && $user->image !== 'profile-default.webp' && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }
        
        // Stocker le nouvel avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->image = $path;
    }

    // Mise à jour des informations de base
    $user->name = $validated['name'];
    $user->phone_number = $validated['phone_number'];

    // Mise à jour du mot de passe si fourni
    if ($request->filled('password')) {
        // Vérifier que le mot de passe actuel est correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect']);
        }
        
        $user->password = Hash::make($validated['password']);
    }

    $user->save();

    return back()->with('success', 'Profil mis à jour avec succès.');
}

public function destroyAvatar()
{
    $user = Auth::user();

    // Ne pas supprimer si c'est déjà l'image par défaut
    if ($user->image && $user->image !== 'profile-default.webp' && Storage::disk('public')->exists($user->image)) {
        Storage::disk('public')->delete($user->image);
        $user->image = 'profile-default.webp'; // Réinitialiser à l'image par défaut
        $user->save();
    }

    return back()->with('success', 'Avatar réinitialisé avec succès');
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
        $user->status = "Actif";
    
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
        $user->status = ($user->status === 'Actif') ? 'Inactif' : 'Actif';

        // Sauvegarder les modifications
        $user->save();

        // Rediriger vers la page précédente avec un message de succès
        return redirect()->route('utilisateurs.index')->with('success', 'Statut mis à jour avec succès');
    }
    
}
